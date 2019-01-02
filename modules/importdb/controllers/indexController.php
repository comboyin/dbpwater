<?php
class indexController extends baseController {
    private $ssh_conn;

    public function index( $arg =  array() ) {

    }

    public function getServerStatus() {
        $serverstatusModel = $this->model->get('ServerStatus');
        $server_status     = $serverstatusModel->getServerStatus();
        $is_busy           = $server_status->getBusyState();
        if ($is_busy == 1) {
            $html = "Server is busy, please try again later";
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 0,
                    "busy_state" => $is_busy,
                    "content" => $html
                )
            );
            exit();
        } elseif ($is_busy == 0) {
            $html = "Server is free";
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 0,
                    "busy_state" => $is_busy,
                    "content" => $html
                )
            );
            exit();
        } else {
            $html = "Cannot get server status ";
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            exit();
        }
    }

    public function checkDatabase($arg = array())
    {
        $host     = trim($_POST['ip']);
        $username = trim($_POST['user']);
        $password = trim($_POST['password']);
        $dbname   = trim($_POST['dbname']);

        try {
            if (empty($host) || empty($username) || empty($password) || empty($dbname)) {
                throw new Exception("Invalid input");
            }
            $connect_result = Common::connect($host, 22);
            $ssh_conn = $this->ssh_conn = $connect_result['connection'];
            if(!$ssh_conn) {
                throw new Exception($connect_result['message']);
            }
            $auth_result = Common::auth_by_pass($ssh_conn, $username, $password);
            if($auth_result['error'] == 1) {
                throw new Exception($auth_result['message']);
            }
            $stream = ssh2_exec($ssh_conn, " mysql -e 'show databases;' ");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $stream_content = stream_get_contents($stream_out);
            $check_database = strpos($stream_content, $dbname);
            if ($check_database) {
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        "check_database" => 1
                    )
                );
            } else {
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        "check_database" => 0
                    )
                );
            }
            exit();
        } catch (Exception $e) {
            $html = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            exit();
        }
    }

    public function importData($arg = array())
    {
        $host     = htmlspecialchars(trim($_POST['ip']));
        $username = htmlspecialchars(trim($_POST['user']));
        $password = htmlspecialchars(trim($_POST['password']));
        $dbname   = htmlspecialchars(trim($_POST['dbname']));
        $env      = htmlspecialchars(trim($_POST['env']));
        $check_database = htmlspecialchars(trim($_POST['check_database']));

        try {
            if (empty($host) || empty($username) || empty($password) || empty($dbname) || empty($env)) {
                throw new Exception("Invalid input");
            }
            //$this->setServerStatus(1);
            /*@var $serverstatusModel serverstatusModel */
            $serverstatusModel = $this->model->get('ServerStatus');
            $serverstatusModel->setServerStatus(1);
            $connect_result = Common::connect($host, 22);
            $ssh_conn = $this->ssh_conn = $connect_result['connection'];
            if(!$ssh_conn) {
                throw new Exception($connect_result['message']);
            }
            $auth_result = Common::auth_by_pass($ssh_conn, $username, $password);
            if($auth_result['error'] == 1) {
                throw new Exception($auth_result['message']);
            }

            $stream_df = ssh2_exec($ssh_conn, "df -h");
            stream_set_blocking($stream_df, true);
            $data_df = "";
            while ($buf = fread($stream_df, 1048576)) {
                $data_df .= $buf;
            }
            if (empty($data_df)) {
                throw new Exception("Cannot check disk free space");
            }
            $data_df_arr = explode('%', $data_df);
            $df_info_arr = explode(" ", $data_df_arr[1]);
            $df_info = $df_info_arr[count($df_info_arr)-3];
            $df = 0;
            if (strpos($df_info, 'M')) {
                throw new Exception("Not enough disk space");
            } elseif (strpos($df_info, 'G')) {
                $df = intval(str_replace('G', '', $df_info));
                if ($df < 2) {
                    throw new Exception("Not enough disk space");
                }
            }

            fclose($stream_df);

            //get file to import
            switch ($env) {
                case "dev":
                    $fileName = Common::getLatestDataFile('dev');
                    break;
                case "pre":
                    $fileName = Common::getLatestDataFile('pre');
                    break;
                case "debug1":
                    $fileName = Common::getLatestDataFile('debug1');
                    break;
                case "debug2":
                    $fileName = Common::getLatestDataFile('debug2');
                    break;
                case "debug3":
                    $fileName = Common::getLatestDataFile('debug3');
                    break;
                case "test":
                    $fileName = Common::getLatestDataFile('test');
                    break;
                default:
                    throw new Exception("Unknown environment");
            }

            $t = time();
            $sqlTmp  = __SITE_PATH . '/sql/tmp_'.$t;
            mkdir($sqlTmp, 0777, true);
            $sqlFileZip =  __SITE_PATH . '/sql/'.$fileName;

            $zip = new ZipArchive;
            $res = $zip->open($sqlFileZip);
            if ($res === TRUE) {
                $zip->extractTo($sqlTmp);
                $zip->close($sqlFileZip);
            } else {
                rmdir($sqlTmp);
                throw new Exception("Could not open zip file");
            }
        } catch (Exception $e) {
            $html = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            //$this->setServerStatus(0);
            $serverstatusModel->setServerStatus(0);
            //disconnect ssh
            ssh2_exec($this->ssh_conn, 'exit;');
            $this->ssh_conn = null;
            exit();
        }

        sleep(10);
        $list = scandir($sqlTmp, 1);
        $sqlFile = $sqlTmp . '/' . $list[0];

        if ($check_database) {
            ssh2_exec($ssh_conn, " mysql -e 'drop database if exists ".$dbname.";' ");
            sleep(90);
            ssh2_exec($ssh_conn, " mysql -e 'create database ".$dbname.";' ");
            sleep(15);
        } else {
            ssh2_exec($ssh_conn, " mysql -e 'create database ".$dbname.";' ");
            sleep(15);
        }
        $command = 'mysql -h '. $host .' -u '. $username .' -p'. $password .' '. $dbname .' < '.$sqlFile;
        exec( $command, $output = array(), $worked );
        switch($worked) {
            case 0:
                $html = 'Data import finished successfully';
                $error = 0;
                break;
            case 1:
                $html= 'There was an error during import';
                $error = 1;
                break;
            default:
                $html= 'Unknown error';
                $error = 1;
        }
        header('Content-Type: application/json');
        echo json_encode(
            array(
                "error" => $error,
                "content" => $html
            )
        );
        unlink($sqlFile);
        rmdir($sqlTmp);
        //$this->setServerStatus(0);
        $serverstatusModel->setServerStatus(0);
        //disconnect ssh
        ssh2_exec($this->ssh_conn, 'exit;');
        $this->ssh_conn = null;
        exit();
    }

}