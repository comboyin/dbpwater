<?php
class indexController extends baseController {
    private $local_server   = 'localhost';
    private $local_dbname   = 'nbook';
    private $local_username = 'root';
    private $local_password = 'lampart';

    public function index( $arg =  array() ) {

    }
    /**
     *
     * @param string $servername
     * @param string $username
     * @param string $password
     * @return array  */
    public function connect_to_server($servername, $username, $password) {
        $result = '';
        $ssh_conn = ssh2_connect($servername , 22);
        if ($ssh_conn) {
            //echo "Connection Successful!" . '<br>';
            $result = array("error" => 0, "message" => "");
        } else {
            $error_conn = 'Connection failed, could not connect to this IP';
            //throw new Exception($error_conn);
            $result = array("error" => 1, "message" => $error_conn);
            return $result;
        }
        $ssh_auth = ssh2_auth_password($ssh_conn, $username, $password);
        if ($ssh_auth) {
            //echo "Authentication Successful!" . '<br>';
            $result = array("error" => 0, "message" => "", "ssh_conn" => $ssh_conn);
        } else {
            $error_auth =  'Authentication failed, incorrect username or password';
            //throw new Exception($error_auth);
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
        //$status = 1;
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

//        $servername = "172.16.149.2";
//        $username   = "root";
//        $password   = "lampart";
//        $dbname     = "testimport";

        try {
            $ssh_conn_arr = $this->connect_to_server($servername, $username, $password);
            if($ssh_conn_arr['error'] == 0) {
                $ssh_conn = $ssh_conn_arr['ssh_conn'];
            } else {
                throw new Exception($ssh_conn_arr['message']);
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

//        $servername = "172.16.149.2";
//        $username   = "root";
//        $password   = "lampart";
//        $dbname     = "testimport";
//        $env        = "test";

        try {
            $this->setServerStatus(1);
            $ssh_conn_arr = $this->connect_to_server($servername, $username, $password);
            if($ssh_conn_arr['error'] == 0) {
                $ssh_conn = $ssh_conn_arr['ssh_conn'];
            } else {
                throw new Exception($ssh_conn_arr['message']);
            }

            //check disk space
            $df = disk_free_space("/");
            if ($df < 2000000000) { //2GB
                throw new Exception('Not enought disk space');
            }

            //get file to import
            //try {
            switch ($env) {
                case "dev":
                    //$fileName = "dev.sql.zip";
                    $fileName = $this->getLatestDataFile('dev');
                    break;
                case "pre":
                    //$fileName = "pre.sql.zip";
                    $fileName = $this->getLatestDataFile('pre');
                    break;
                case "debug1":
                    //$fileName = "debug1.sql.zip";
                    $fileName = $this->getLatestDataFile('debug1');
                    break;
                case "debug2":
                    //$fileName = "debug2.sql.zip";
                    $fileName = $this->getLatestDataFile('debug2');
                    break;
                case "debug3":
                    //$fileName = "debug3.sql.zip";
                    $fileName = $this->getLatestDataFile('debug3');
                    break;
                case "test":
                    //$fileName = "mediatek.sql.zip";
                    $fileName = $this->getLatestDataFile('test');
                    break;
                default:
                    throw new Exception("Unknown environment");
            }

            $t = time();
            $sqlTmp  = __SITE_PATH . '/sql/tmp_'.$t;
            mkdir($sqlTmp, 0777, true);
            $sqlFileZip =  __SITE_PATH . '/sql/'.$fileName;
            //$sqlFile  = __SITE_PATH . '/sql/mediatek.sql';

            $zip = new ZipArchive;
            $res = $zip->open($sqlFileZip);
            if ($res === TRUE) {
                // extract it to the path we determined above
                $zip->extractTo($sqlTmp);
                $zip->close($sqlFileZip);
                //echo "WOOT! file extracted to $sqlTmp";
            } else {
                //echo "Doh! I couldn't open file";
                rmdir($sqlTmp);
                throw new Exception("Could not open zip file");
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

        } catch (Exception $e) {
            //echo 'Error: ',  $e->getMessage(), "\n";
            $html = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            $this->setServerStatus(0);
            exit();
        }

        $command = 'mysql -h '. $servername .' -u '. $username .' -p'. $password .' '. $dbname .' < '.$sqlFile;
        exec( $command, $output = array(), $worked );
        //var_dump($worked);
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