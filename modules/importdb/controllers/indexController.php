<?php
class indexController extends baseController {
    private $ssh_conn;

    public function index( $arg =  array() ) {

    }

    /**
     *
     * @param string $servername
     * @param string $username
     * @param string $password
     * @return array  */
    public function connect_to_server($servername, $username, $password) {
        $ssh_conn = $this->ssh_conn = ssh2_connect($servername , 22);
        if ($ssh_conn) {
            $result = array("error" => 0, "message" => "");
        } else {
            $error_conn = 'Connection failed, could not connect to this IP';
            $result = array("error" => 1, "message" => $error_conn);
            return $result;
        }
        $ssh_auth = ssh2_auth_password($ssh_conn, $username, $password);
        if ($ssh_auth) {
            $result = array("error" => 0, "message" => "");
        } else {
            $error_auth =  'Authentication failed, incorrect username or password';
            $result = array("error" => 1, "message" => $error_auth);
            return $result;
        }
        return $result;
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

    /**
     *
     * @param int $status
     * @return int|$row_count  */
    public function setServerStatus($status) {
        $serverstatusModel = $this->model->get('ServerStatus');
        $row_count         = $serverstatusModel->setServerStatus($status);
        return $row_count;
    }

    public function checkDatabase($arg = array())
    {
        $servername = trim($_POST['ip']);
        $username   = trim($_POST['user']);
        $password   = trim($_POST['password']);
        $dbname     = trim($_POST['dbname']);

        if (empty($servername) || empty($username) || empty($password) || empty($dbname)) {
            throw new Exception("Invalid input");
        }
        try {
            $connect_result = $this->connect_to_server($servername, $username, $password);
            $ssh_conn = $this->ssh_conn;
            if(!$ssh_conn) {
                throw new Exception($connect_result['message']);
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
        $servername = htmlspecialchars(trim($_POST['ip']));
        $username   = htmlspecialchars(trim($_POST['user']));
        $password   = htmlspecialchars(trim($_POST['password']));
        $dbname     = htmlspecialchars(trim($_POST['dbname']));
        $env        = htmlspecialchars(trim($_POST['env']));
        $check_database = htmlspecialchars(trim($_POST['check_database']));

        try {
            $this->setServerStatus(1);
            $connect_result = $this->connect_to_server($servername, $username, $password);
            $ssh_conn = $this->ssh_conn;
            if(!$ssh_conn) {
                throw new Exception($connect_result['message']);
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
                    $fileName = $this->getLatestDataFile('dev');
                    break;
                case "pre":
                    $fileName = $this->getLatestDataFile('pre');
                    break;
                case "debug1":
                    $fileName = $this->getLatestDataFile('debug1');
                    break;
                case "debug2":
                    $fileName = $this->getLatestDataFile('debug2');
                    break;
                case "debug3":
                    $fileName = $this->getLatestDataFile('debug3');
                    break;
                case "test":
                    $fileName = $this->getLatestDataFile('test');
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
            $this->setServerStatus(0);
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
        $command = 'mysql -h '. $servername .' -u '. $username .' -p'. $password .' '. $dbname .' < '.$sqlFile;
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
        $this->setServerStatus(0);
        //disconnect ssh
        ssh2_exec($this->ssh_conn, 'exit;');
        $this->ssh_conn = null;
        exit();
    }

    function getLatestDataFile($env = '') {
        $dir  = __SITE_PATH . '/sql/';
        $file_list = scandir($dir, 1);
        $data_files = array();
        foreach ($file_list as $item) {
            $find = $env.'.sql';
            if (strpos($item, $find) !== false ) {
                $data_files[] = $item;
            }
        }
        $max = 0;
        $file = '';
        if (!empty($data_files)) {
            foreach ($data_files as $value) {
                $file_path = $dir.$value;
                $creation_date = filectime($file_path);
                if ($creation_date > $max) {
                    $max = $creation_date;
                    $file = $value;
                }
            }
        } else {
            throw new Exception('Data file does not exist');
        }
        if (!empty($file)) {
            return $file;
        }
        return false;
    }

}