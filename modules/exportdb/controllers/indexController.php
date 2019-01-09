<?php
class indexController extends baseController {
    private $ssh_host = '';
    private $ssh_port = 22;
    private $connection;

    public function index( $arg =  array() ) {

    }

    public function exportData() {
        $environment       = $this->registry->environment;
        $server_db_name    = $this->registry->server_db_name;
        $server_db_pass    = $this->registry->server_db_pass;
        $server_passphrase = $this->registry->server_passphrase;
        $server_auth_user  = $this->registry->server_auth_user;
        $ssh_keyfiles      = $this->registry->ssh_keyfiles;
        $pubkeyfile        = $ssh_keyfiles['pubkeyfile'];
        $privkeyfile       = $ssh_keyfiles['privkeyfile'];
        $env = htmlspecialchars(trim($_POST['env']));

        $config_file  = __SITE_PATH . '/sql/config_data.txt';
        $alldata_file = 'alldata.txt';
        $nodata_file  = 'nodata.txt';
        $d = date("Y_m_d_H_i_s");

        try {
            switch ($env) {
                case "dev":
                    $this->ssh_host = $environment['dev'];
                    $db_name = $server_db_name['dev'];
                    $passphrase = $server_passphrase['dev'];
                    $db_pass = $server_db_pass['dev'];
                    $auth_user =  $server_auth_user['dev'];
                    $sql_file = 'dev_'.$d.'.sql';
                    $zip_file = 'dev.sql_'.$d.'.zip';
                    break;
                case "pre":
                    $this->ssh_host = $environment['pre'];
                    $db_name = $server_db_name['pre'];
                    $db_pass = $server_db_pass['pre'];
                    $passphrase = $server_passphrase['pre'];
                    $auth_user =  $server_auth_user['pre'];
                    $sql_file = 'pre_'.$d.'.sql';
                    $zip_file = 'pre.sql_'.$d.'.zip';
                    break;
                case "debug1":
                    $this->ssh_host = $environment['debug1'];
                    $db_name = $server_db_name['debug1'];
                    $db_pass = $server_db_pass['debug1'];
                    $passphrase = $server_passphrase['debug1'];
                    $auth_user =  $server_auth_user['debug1'];
                    $sql_file = 'debug1_'.$d.'.sql';
                    $zip_file = 'debug1.sql_'.$d.'.zip';
                    break;
                case "debug2":
                    $this->ssh_host = $environment['debug2'];
                    $db_name = $server_db_name['debug2'];
                    $db_pass = $server_db_pass['debug2'];
                    $passphrase = $server_passphrase['debug2'];
                    $auth_user =  $server_auth_user['debug2'];
                    $sql_file = 'debug2_'.$d.'.sql';
                    $zip_file = 'debug2.sql_'.$d.'.zip';
                    break;
                case "debug3":
                    $this->ssh_host = $environment['debug3'];
                    $db_name = $server_db_name['debug3'];
                    $db_pass = $server_db_pass['debug3'];
                    $passphrase = $server_passphrase['debug3'];
                    $auth_user =  $server_auth_user['debug3'];
                    $sql_file = 'debug3_'.$d.'.sql';
                    $zip_file = 'debug3.sql_'.$d.'.zip';
                    break;
                case "test":
                    $this->ssh_host = $environment['test'];
                    $db_name = $server_db_name['test'];
                    $db_pass = $server_db_pass['test'];
                    $passphrase = $server_passphrase['test'];
                    $auth_user =  $server_auth_user['test'];
                    $sql_file = 'test_'.$d.'.sql';
                    $zip_file = 'test.sql_'.$d.'.zip';
                    $config_file  = __SITE_PATH . '/sql/test_config_data.txt';
                    break;
                default:
                    throw new Exception("Unknown environment");
            }
            $connect_result = Common::connect($this->ssh_host, $this->ssh_port, array('hostkey'=>'ssh-rsa'));
            $ssh_conn = $this->connection = $connect_result['connection'];
            if(!$ssh_conn) {
                throw new Exception($connect_result['message']);
            }
            //$auth_result = Common::auth_by_pass($ssh_conn, $auth_user, 'lampart');
            if (!file_exists($pubkeyfile) || !is_readable($pubkeyfile)) {
                throw new \Exception("Public key file stored in '{$pubkeyfile}' was not found or is not readable");
            }
            if (!file_exists($privkeyfile) || !is_readable($privkeyfile)) {
                throw new \Exception("Private key file stored in '{$privkeyfile}' was not found or is not readable");
            }
            $auth_result = Common::auth_by_public_key($ssh_conn, $auth_user, $pubkeyfile, $privkeyfile, $passphrase);
            if($auth_result['error'] == 1) {
                throw new Exception($auth_result['message']);
            }
            $config = Common::getConfigFromFile($config_file);
            if (empty($config)) {
                throw new Exception("Config error");
            }

            $alldata_table_arr = array();
            $nodata_table_arr  = array();
            foreach ($config as $config_item) {
                if ($config_item['get_data']) {
                    $alldata_table_arr[] = $config_item['table'];
                } else {
                    $nodata_table_arr[] = $config_item['table'];
                }
            }
            $flag1 = $flag2 = false;
            if (count($alldata_table_arr) > 200) {
                $alldata_table_arr = array_chunk($alldata_table_arr, 200);
                $alldata_table_str_1 = implode(" ", $alldata_table_arr[0]);
                $alldata_table_str_2 = implode(" ", $alldata_table_arr[1]);
                $alldata_file_1 = 'alldata1.txt';
                $cmd_alldata_1 = 'sudo mysqldump -u'.$auth_user.' -p'.$db_pass.' --max_allowed_packet=1G '.$db_name.' '.$alldata_table_str_1.' > "'.$alldata_file_1.'"';
                Common::exec($ssh_conn, $cmd_alldata_1);
                $alldata_file_2 = 'alldata2.txt';
                $cmd_alldata_2 = 'sudo mysqldump -u'.$auth_user.' -p'.$db_pass.' --max_allowed_packet=1G '.$db_name.' '.$alldata_table_str_2.' > "'.$alldata_file_2.'"';
                Common::exec($ssh_conn, $cmd_alldata_2);
                $cmd_join_alldata = 'sudo cat '.$alldata_file_1.' '.$alldata_file_2.' > '.$alldata_file.'; ';
                Common::exec($ssh_conn, $cmd_join_alldata );
                $flag1 = true;
            } else {
                if (count($alldata_table_arr) > 0) {
                    $alldata_table_str = implode(" ", $alldata_table_arr);
                    $cmd_alldata = 'sudo mysqldump -u'.$auth_user.' -p'.$db_pass.' --max_allowed_packet=1G '.$db_name.' '.$alldata_table_str.' > "'.$alldata_file.'"';
                    Common::exec($ssh_conn, $cmd_alldata);
                    $flag1 = true;
                }
            }
            if (count($nodata_table_arr) > 200) {
                $nodata_table_arr = array_chunk($alldata_table_arr, 200);
                $nodata_table_str_1 = implode(" ", $nodata_table_arr[0]);
                $nodata_table_str_2 = implode(" ", $nodata_table_arr[1]);
                $nodata_file_1 = 'nodata1.txt';
                $cmd_nodata_1 = 'sudo mysqldump -u'.$auth_user.' -p'.$db_pass.' --max_allowed_packet=1G --no-data '.$db_name.' '.$nodata_table_str_1.' > "'.$nodata_file_1.'"';
                Common::exec($ssh_conn, $cmd_nodata_1);
                $nodata_file_2 = 'nodata2.txt';
                $cmd_nodata_2 = 'sudo mysqldump -u'.$auth_user.' -p'.$db_pass.' --max_allowed_packet=1G --no-data '.$db_name.' '.$nodata_table_str_2.' > "'.$nodata_file_2.'"';
                Common::exec($ssh_conn, $cmd_nodata_2);
                $cmd_join_nodata = 'sudo cat '.$nodata_file_1.' '.$nodata_file_2.' > '.$nodata_file.'; ';
                $cmd_join_nodata .= 'sudo rm -rf '.$nodata_file_1.'; ';
                $cmd_join_nodata .= 'sudo rm -rf '.$nodata_file_2.'; ';
                Common::exec($ssh_conn, $cmd_join_nodata );
                $flag2 = true;
            } else {
                if (count($nodata_table_arr) > 0) {
                    $nodata_table_str = implode(" ", $nodata_table_arr);
                    $cmd_nodata = 'sudo mysqldump -u' . $auth_user . ' -p' . $db_pass . ' --max_allowed_packet=1G --no-data ' . $db_name . ' ' . $nodata_table_str . ' > "' . $nodata_file . '"';
                    Common::exec($ssh_conn, $cmd_nodata);
                    $flag2 = true;
                }
            }
            if ($flag1 && $flag2) {
                $cmd_join_files = 'sudo cat '.$alldata_file.' '.$nodata_file.' > '.$sql_file.'; ';
                $cmd_join_files .= 'sudo rm -rf '.$alldata_file.'; ';
                $cmd_join_files .= 'sudo rm -rf '.$nodata_file.'; ';
                $cmd_join_files .= 'sudo zip -r '.$zip_file.' '.$sql_file.'; ';
                $cmd_join_files .= 'sudo rm -rf '.$sql_file.';';
                $run_cmd_joinfiles = Common::exec($ssh_conn, $cmd_join_files);
            } elseif ($flag1) {
                $cmd_zip_file = 'mv '.$alldata_file.' '.$sql_file.'; ';
                $cmd_zip_file .= 'sudo zip -r '.$zip_file.' '.$sql_file.'; ';
                $cmd_zip_file .= 'sudo rm -rf '.$sql_file.';';
                $run_cmd_zip_file = Common::exec($ssh_conn, $cmd_zip_file);
            } elseif ($flag2) {
                $cmd_zip_file = 'mv '.$nodata_file.' '.$sql_file.'; ';
                $cmd_zip_file .= 'sudo zip -r '.$zip_file.' '.$sql_file.'; ';
                $cmd_zip_file .= 'sudo rm -rf '.$sql_file.';';
                $run_cmd_zip_file = Common::exec($ssh_conn, $cmd_zip_file);
            } else {
                throw new Exception("Unable to create file: $sql_file");
            }
            //Copy file from the remote server to the local filesystem
            $local_sql_folder = __SITE_PATH . '/sql';
            $sftp_conn = ssh2_sftp($this->connection);
            if (!$sftp_conn) {
                throw new Exception('Unable to create SFTP connection.');
            }
            $remote_stream = fopen("ssh2.sftp://$sftp_conn/root/$zip_file", 'r');
            if (!$remote_stream) {
                throw new Exception("Unable to open remote file: $zip_file");
            }
            $local_stream = fopen("$local_sql_folder/$zip_file", 'w');
            if (!$local_stream) {
                throw new Exception("Unable to open local file for writing: $local_sql_folder/$zip_file");
            }
            $read = 0;
            $file_size = filesize("ssh2.sftp://$sftp_conn/root/$zip_file");
            while ($read < $file_size && ($buffer = fread($remote_stream, $file_size - $read))) {
                // Increase bytes read
                $read += strlen($buffer);
                // Write to local file
                if (fwrite($local_stream, $buffer) === FALSE) {
                    throw new Exception("Unable to write to local file: $local_sql_folder/$zip_file");
                }
            }
            // Close streams
            fclose($local_stream);
            fclose($remote_stream);
            //disconnect ssh
            ssh2_exec($this->connection, 'exit;');
            $this->connection = null;
            $html = 'Data export finished successfully';
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 0,
                    "content" => $html
                )
            );
            exit();
        }
        catch (Exception $e) {
            $html = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    "error" => 1,
                    "content" => $html
                )
            );
            //disconnect ssh
            ssh2_exec($this->connection, 'exit;');
            $this->connection = null;
            exit();
        }
    }

}