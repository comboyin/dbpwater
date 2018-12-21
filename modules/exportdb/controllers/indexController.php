<?php
class indexController extends baseController {

    public function index( $arg =  array() ) {

    }

    public function connectdata($servername, $dbname, $username, $password) {

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->exec("set names utf8");
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return array('error'=>0, 'message'=>'', 'pdo'=>$pdo);
        }
        catch(PDOException $e)
        {
            //echo "Database connection failed: " . $e->getMessage();
//            $html = $e->getMessage();
//            header('Content-Type: application/json');
//            echo json_encode(
//                array(
//                    "error" => 1,
//                    "content" => $html
//                )
//            );
//            exit();
            return array('error'=>1, 'message'=>$e->getMessage());
        }
    }

    public function exportData() {
        $servername = htmlspecialchars(trim($_POST['host']));
        $username   = htmlspecialchars(trim($_POST['user']));
        $password   = htmlspecialchars(trim($_POST['password']));
        $dbname     = htmlspecialchars(trim($_POST['dbname']));
        $env        = htmlspecialchars(trim($_POST['env']));

//        $servername = '172.16.149.2';
//        $username   = 'root';
//        $password   = 'lampart';
//        $dbname     = 'test2';
//        $env        = 'dev';

        try {
            $connect = $this->connectdata($servername, $dbname, $username, $password);
            $pdo = $connect['pdo'];
            if(!empty($pdo)) {
                //echo 'Connected successfully<br>';
//                $html = 'Connected successfully';
//                header('Content-Type: application/json');
//                echo json_encode(
//                    array(
//                        "error" => 0,
//                        "content" => $html
//                    )
//                );
//                exit();

                $config_file = __SITE_PATH . '/sql/config_data.txt';
                if (!file_exists($config_file)) {
                    throw new Exception('Cannot open config file');
                }
                $config_lines = file($config_file);
                //echo '<pre>';var_dump($config_lines);echo '</pre><br>';
                $tables = array();
                foreach ($config_lines as $config) {
                    $tmp_arr = explode(":", $config);
                    //echo '<pre>';var_dump($tmp_arr);echo '</pre><br>';
                    $tables[] = array('table'=>$tmp_arr[0], 'get_data'=>$tmp_arr['1']);
                }
                $content = '';
                foreach ($tables as $item) {
                    try {
                        $content .= 'DROP TABLE IF EXISTS '."`".$item['table']."`".';';
                        $sql1 = 'SHOW CREATE TABLE '."`".$item['table']."`";
                        //$sql1 = 'select * from mtk_content';
                        $sth = $pdo->prepare($sql1);
                        $sth->execute();
                        $result = $sth->fetch(PDO::FETCH_ASSOC);
                        $content .= "\n\n".$result['Create Table'].";\n\n";
                    }
                    catch (\PDOException $e) {
                        $html = $e->getMessage();
                        header('Content-Type: application/json');
                        echo json_encode(
                            array(
                                "error" => 1,
                                "content" => $html
                            )
                        );
                        $pdo = null;
                        exit();
                    }

                    if ($item['get_data'] == -1) {
                        try {
                            $sql2 = 'SHOW COLUMNS FROM '."`".$item['table']."`";
                            $sth = $pdo->prepare($sql2);
                            $sth->execute();
                            $column_names = $sth->fetchAll(PDO::FETCH_COLUMN);
                            $column_count = count($column_names);

                            $sql3 = 'SELECT * FROM '.$item['table'];
                            $sth = $pdo->prepare($sql3);
                            $sth->execute();
                        }
                        catch (\PDOException $e) {
                            $html = $e->getMessage();
                            header('Content-Type: application/json');
                            echo json_encode(
                                array(
                                    "error" => 1,
                                    "content" => $html
                                )
                            );
                            $pdo = null;
                            exit();
                        }

                        //$row_count = $sth->rowCount();
                        $result = $sth->fetchAll();
                        foreach ($result as $value) {
                            $head = '';
                            $head .= "INSERT INTO " . "`" .$item['table']. "` (" ;
                            foreach ($column_names as $key=>$column_name) {
                                if ($key != $column_count - 1) {
                                    $head .= "`".$column_name."`, ";
                                } else {
                                    $head .= "`".$column_name."`";
                                }
                            }
                            $head .=") \n";
                            $content .= $head;
                            $content .= "VALUES (";
                            foreach ($column_names as $key=>$column_name) {
                                if (!empty($value[$column_name])) {
                                    $text = addslashes($value[$column_name]);
                                    $content  .= "'".$text."'";
                                } else {
                                    $content .= "''";
                                }

                                if($key < $column_count-1) {
                                    $content .=",";
                                }
                            }
                            $content .= ");\n\n";
                        }
                    }

                }
//                echo get_class_methods($pdo);
//                echo '<pre>';var_dump($pdo);echo '</pre><br>';
//                echo '<pre>columns: ';var_dump($column_names);echo '</pre><br>';
//                echo '<pre>';var_dump($tables);echo '</pre><br>';
//                echo '<pre>';var_dump($row_count);echo '</pre><br>';
//                echo '<pre>result: ';var_dump($result);echo '</pre><br>';
                $sql_file = __SITE_PATH . '/sql/data.sql';
                file_put_contents($sql_file, $content);

                //Create zip file
                $t = time();
                $d = date("Y_m_d_H_i_s", $t);
                switch ($env) {
                    case "dev":
                        $zip_file = __SITE_PATH . '/sql/dev.sql_'.$d.'.zip';
                        break;
                    case "pre":
                        $zip_file = __SITE_PATH . '/sql/pre.sql_'.$d.'.zip';
                        break;
                    case "debug1":
                        $zip_file = __SITE_PATH . '/sql/debug1.sql_'.$d.'.zip';
                        break;
                    case "debug2":
                        $zip_file = __SITE_PATH . '/sql/debug2.sql_'.$d.'zip';
                        break;
                    case "debug3":
                        $zip_file = __SITE_PATH . '/sql/debug3.sql_'.$d.'.zip';
                        break;
                    case "test":
                        $zip_file = __SITE_PATH . '/sql/test.sql_'.$d.'.zip';
                        break;
                    default:
                        throw new Exception("Unknown environment");
                }
                //$zip_file = __SITE_PATH . '/sql/dev.sql_'.$d.'.zip';

                $zip = new ZipArchive();
                if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile($sql_file, 'data.sql');
                    $zip->close();
                    $pdo = null;
                    unlink($sql_file);
                    $html = 'Data export finished successfully';
                    header('Content-Type: application/json');
                    echo json_encode(
                        array(
                            "error" => 0,
                            "content" => $html
                        )
                    );
                    exit();
                } else {
                    $pdo = null;
                    unlink($sql_file);
                    throw new Exception('Cannot create zip file');
                }

            } else {
                throw new Exception($connect['message']);
            }
        } catch (Exception $e) {
            //echo $e->getMessage();
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

}