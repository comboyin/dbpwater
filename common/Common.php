<?php
class Common {
    /**
     *
     * @param string $host
     * @param int $port
     * @return array
     */
    static public function connect($host, $port, $method = array()) {
        if (!empty($method)) {
            $ssh_conn = ssh2_connect($host, $port, $method);
        } else {
            $ssh_conn = ssh2_connect($host, $port);
        }
        if ($ssh_conn) {
            $result = array("error" => 0, "message" => "Connected to server", "connection" => $ssh_conn);
        } else {
            $result = array("error" => 1, "message" => "Cannot connect to server", "connection" => null);
        }
        return $result;
    }

    /**
     *
     * @param resource $connection
     * @param string $username
     * @param string $password
     * @return array
     */
    static public function auth_by_pass($connection, $username, $password) {
        $ssh_auth = ssh2_auth_password($connection, $username, $password);
        if ($ssh_auth) {
            $result = array("error" => 0, "message" => "Authenticated");
        } else {
            $result = array("error" => 1, "message" => "Authentication failed, incorrect username or password");
        }
        return $result;
    }

    /**
     *
     * @param resource $connection
     * @param string $username
     * @param string $pubkeyfile
     * @param string $privkeyfile
     * @param string $passphrase
     * @return array $result
     */
    static public function auth_by_public_key($connection, $username, $pubkeyfile, $privkeyfile, $passphrase = '') {
        $ssh_auth = ssh2_auth_pubkey_file($connection, $username, $pubkeyfile, $privkeyfile, $passphrase);
        if ($ssh_auth) {
            $result = array("error" => 0, "message" => "Public Key Authentication Successful");
        } else {
            $result = array("error" => 1, "message" => "Public Key Authentication Failed");
        }
        return $result;
    }

    /**
     *
     * @param resource $connection
     */
    static public function disconnect($connection) {
        try {
            $cmd= 'echo "EXITING" && exit;';
            self::exec($connection, $cmd);
            $connection = null;
        } catch (Exception $e) {

        }
    }

    /**
     *
     * @param resource $connection
     * @param string $cmd
     * @return array
     */
    static public function exec($connection, $cmd) {
        if (!($stream = ssh2_exec($connection, $cmd))) {
            throw new Exception('SSH command failed');
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 1048576)) {
            $data .= $buf;
        }
        fclose($stream);
//        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
//        $data       = stream_get_contents($stream_out);
        return array('success' => true, 'stream' => $stream, 'data' => $data);
    }

    /**
     *
     * @param string $env
     * @return string $file
     */
    static public function getLatestDataFile($env = '') {
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

    /**
     *
     * @param string $file_path
     * @return array $config
     */
    static public function getConfigFromFile($file_path) {
        $config_file = $file_path;
        if (!file_exists($config_file)) {
            throw new Exception('Cannot open config file');
        }
        $config_lines = file($config_file);
        $config = array();
        foreach ($config_lines as $item) {
            if (!empty($item)) {
                $tmp_arr = explode(":", $item);
                $config[] = array('table'=>trim($tmp_arr[0]), 'get_data'=>trim($tmp_arr['1']));
            }
        }
        return $config;
    }

    static public function getConfigFromDatabase() {

    }

}
