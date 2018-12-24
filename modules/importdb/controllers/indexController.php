<?php
class indexController extends baseController {
    private $local_server   = 'localhost';
    private $local_dbname   = 'nbook';
    private $local_username = 'root';
    private $local_password = 'lampart';

    public function index( $arg =  array() ) {

    }

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

    public function getServerStatus($arg = array()) {
        try {
            $pdo = new PDO("mysql:host=$this->local_server;dbname=$this->local_dbname", $this->local_username, $this->local_password);
            $pdo->exec("set names utf8");
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM server_status';
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            //var_dump($result);
            //return $result['is_busy'];
            $html = "Server is busy, please try again later";
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 0,
                    "server_status" => $result['is_busy'],
                    "content" => $html
                )
            );
            exit();

        }
        catch(PDOException $e)
        {
            $html = "Cannot get server status " . $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            exit();
            //return array('error'=>1, 'message'=>$e->getMessage());
        }
    }

    public function setServerStatus($status) {
        //$status = 0;
        try {
            $pdo = new PDO("mysql:host=$this->local_server;dbname=$this->local_dbname", $this->local_username, $this->local_password);
            $pdo->exec("set names utf8");
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'UPDATE server_status SET is_busy = '.$status;
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $row_count = $sth->rowCount();
            return $row_count;

        }
        catch(PDOException $e)
        {
//            $html = $e->getMessage();
//            header('Content-Type: application/json');
//            echo json_encode(
//                array(
//                    "error" => 1,
//                    "content" => $html
//                )
//            );
//            exit();
            return false;
        }
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

    public function importData__($arg = array())
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
//        $env        = "dev";
//        $env        = "test";

        try {
//            $ssh_conn = ssh2_connect($servername , 22);
//            $ssh_auth = ssh2_auth_password($ssh_conn, $username, $password);
//            if ($ssh_conn) {
//                //echo "Connection Successful!" . '<br>';
//            } else {
//                $error_conn = 'Connection Failed';
//                throw new Exception($error_conn);
//            }
//            if ($ssh_auth) {
//                //echo "Authentication Successful!" . '<br>';
//            } else {
//                $error_auth =  'Authentication Failed';
//                throw new Exception($error_auth);
//            }

            $ssh_conn_arr = $this->connect_to_server($servername, $username, $password);
            if($ssh_conn_arr['error'] == 0) {
                $ssh_conn = $ssh_conn_arr['ssh_conn'];
            } else {
                throw new Exception($ssh_conn_arr['message']);
            }
            //$stream = ssh2_exec($ssh_conn, " mysql -e 'show databases;' ");
            //stream_set_blocking($stream, true);
            //$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);

            //$stream_content = stream_get_contents($stream_out);

//            $str = '<br>stream content: ';var_dump($stream_content);echo '<br>';
            //ssh2_exec($ssh_conn, " mysql -e 'purge binary logs before NOW() ' ");
            //sleep(15);
            //$check_database = strpos($stream_content, $dbname);

            //check disk space
//            $is_free = true;
//            $stream = ssh2_exec($ssh_conn, "df -h");
//            stream_set_blocking($stream, true);
//            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
//            $stream_content = stream_get_contents($stream_out);
//            //echo $stream_content2;echo '<br>';
//            $stream_content_arr = explode(" ", $stream_content);
//            $free_space = $stream_content_arr[44];
//            if (strpos($free_space, 'M')) {
//                //$free_space = str_replace('M', '', $free_space);
//                $is_free = false;
//            }
//            if (strpos($free_space, 'G')) {
//                $free_space = intval(str_replace('G', '', $free_space));
//                if($free_space < 2) {
//                    $is_free = false;
//                }
//            }
//
//            if (!$is_free) {
//                throw new Exception('Not enought disk space');
//            }
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
                //$files = array_diff($list, array('.','..'));
                //echo '<br><pre>';var_dump($list);echo '</pre>';
                $sqlFile = $sqlTmp . '/' . $list[0];
                //echo '<br><pre>';var_dump($sqlFile);echo '</pre>';
//            }
//            catch (Exception $e) {
//                $html = $e->getMessage();
//                header('Content-Type: application/json');
//                echo json_encode(
//                    array(
//                        "error" => 1,
//                        "content" => $html
//                    )
//                );
//                exit();
//            }

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
            exit();
            //return false;
        }

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        }
        catch(PDOException $e)
        {
            //echo "Database connection failed: " . $e->getMessage();
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
        if (!empty($conn)) {
            $pdo = $conn;
            try {
                // Enable LOAD LOCAL INFILE
                $pdo->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);

                //$errorDetect = false;

                // Temporary variable, used to store current query
                $tmpLine = '';

                // Read in entire file
                $lines = file($sqlFile);

                // Loop through each line
                foreach ($lines as $line) {
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || trim($line) == '') {
                        continue;
                    }

                    // Read & replace prefix
                    //$line = str_replace(['<<prefix>>', '<<InFilePath>>'], [$tablePrefix, $InFilePath], $line);

                    // Add this line to the current segment
                    $tmpLine .= $line;

                    // If it has a semicolon at the end, it's the end of the query
                    if (substr(trim($line), -1, 1) == ';') {
                        try {
                            // begin the transaction
                            $pdo->beginTransaction();
                            // Perform the Query
                            $pdo->exec($tmpLine);
                            // commit the transaction
                            $conn->commit();
                        } catch (\PDOException $e) {
                            $pdo->rollback();
                            //echo "<br><pre>Error performing Query: '<strong>" . $tmpLine . "</strong>': " . $e->getMessage() . "</pre>\n";
                            //$errorDetect = true;
                            $html = $e->getMessage();
                            header('Content-Type: application/json');
                            echo json_encode(
                                array(
                                    "error" => 1,
                                    "content" => $html
                                )
                            );
                            $pdo = null;
                            unlink($sqlFile);
                            rmdir($sqlTmp);
                            exit();
                        }

                        // Reset temp variable to empty
                        $tmpLine = '';
                    }
                }
                // Check if error is detected
//                if ($errorDetect) {
//                    return false;
//                }
            } catch (\Exception $e) {
                //echo "<br><pre>Exception => " . $e->getMessage() . "</pre>\n";
                $html = $e->getMessage();
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        "error" => 1,
                        "content" => $html
                    )
                );
                $pdo = null;
                unlink($sqlFile);
                rmdir($sqlTmp);
                exit();
            }
            $pdo = null;
            unlink($sqlFile);
            rmdir($sqlTmp);

            $html = "Data import finished successfully";
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 0,
                    "content" => $html
                )
            );
            exit();
        }

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