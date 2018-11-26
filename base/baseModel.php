<?php
class baseModel
{

    private static $instance;

    private $pdo = null;

    private $registry = null;

    /**
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     *
     * @param the $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    function __construct($registry = null)
    {
    	if($registry != null){
    		$this->registry = $registry;
    		$this->openConnection();
    	}


    }

    public function openConnection(){
    	$database = $this->registry->database;
    	$_servername = $database['db_servername'];
    	$_dbname = $database['db_dbname'];
    	$_username = $database['db_username'];
    	$_password = $database['db_password'];
    	try {
    		if($this->pdo == null){
    			$this->pdo = new PDO (
    			    "mysql:host=$_servername;dbname=$_dbname", $_username, $_password,
    			    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		}

    		return $this->pdo;
    	} catch ( PDOException $e ) {
    		return false;
    	}
    }

    public function closeConnection(){
        if($this->pdo != null){
            $this->pdo = null;
        }
    }
    public static function getInstance($registry)
    {

        if (!self::$instance) {

            self::$instance = new baseModel($registry);

        }
        return self::$instance;
    }

    public function get($name)
    {
        $file = __SITE_PATH . '/models/' . str_replace("model", "", strtolower($name)) . "Model.php";

        if (file_exists($file)) {
            include_once $file;
            $class = str_replace("model", "", strtolower($name)) . "Model";
            $model = new $class($this->registry);
            return $model;
        }
        return NULL;
    }

    public function __destruct(){
    	if($this->pdo!=null){
    		$this->pdo=null;
    	}
    }
    
    /**
     * update table 
     * return null => success
     * return array => error
     * example:
     * 		$tableName = " user "
     * 		$setValue  = " set fullname = tranminhnhut "
     * 		$stringWhere = " where id = 1 "
     * @param string $tableName
     * @param string $setValue
     * @param string $stringWhere  
     * @return NULL|array 
     * */
    public function updateTableByWhere( $tableName, $setValue, $stringWhere ) {
    	$is_error = null;
    	try {
    		 $lowerCaseTableName = strtolower( $tableName );
    		 $sql = " UPDATE $lowerCaseTableName $setValue $stringWhere ";
    		 
    		 $stmt = $this->getPdo()->prepare( $sql );
    		 $stmt->execute();
    	} catch (Exception $e) {
    		//echo $e->getMessage();
    		$is_error[] = $e->getMessage();	
    	}
    	return $is_error;
    }
    
    /**
     * DELETE table
     * return null => success
     * return array => error
     * example:
     * 		$tableName = " user "
     * 		
     * 		$stringWhere = " where id = 1 "
     * @param string $tableName
     * @param string $setValue
     * @param string $stringWhere
     * @return NULL|array
     * */
    public function deleteTableByWhere( $tableName, $stringWhere ) {
    	$is_error = null;
    	try {
    		$lowerCaseTableName = strtolower( $tableName );
    		$lowerCaseTableName = " `$lowerCaseTableName` ";
    		$sql = " DELETE FROM $lowerCaseTableName $stringWhere ";
    		 
    		$stmt = $this->getPdo()->prepare( $sql );
    		$stmt->execute();
    	} catch (Exception $e) {
    		//echo $e->getMessage();
    		$is_error[] = $e->getMessage();
    	}
    	return $is_error;
    }
    
    public function listTableByWhere( $StringTable, $stringWhere , $option = array() ){
    	
    	$listObj = array();
    	
    	try {
    		// create string sql
    		$string = "";
    		if( !empty( $stringWhere ) ){
    			foreach ( $stringWhere as $key=>$where ){
    				if( $key == 0 ){
    					$string .= " WHERE $where ";
    				}else{
    					$string .= " and $where ";
    				}
    			}
    		}
    		
    		$nameTable = strtolower($StringTable);
    		$nameTable = " `$nameTable` ";
    		if( $option == null ){
    			$sql = " SELECT * FROM $nameTable $string ORDER BY id desc ";
    		} else{
    			$start = $option['start'];
    			$limit = $option['limit'];
    			$sql = " SELECT * FROM $nameTable $string ORDER BY id desc LIMIT $start,$limit ";
    		}
    
    		$sth = $this->getPdo()->prepare($sql);
    
    		$sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $StringTable);
    		$sth->execute();
    
    		$listObj = $sth->fetchAll();
    
    		return $listObj;
    
    	} catch (Exception $e) {
    		
    		echo $e->getMessage();
    		
    	}
    	return $listObj;
    }
    
    
    
    /**
     * Total recore in table from where
     * @param string $StringTable
     * @param array $stringWhere
     * @param array $option  
     * @return int 
     * */
    public function countListTableByWhere( $StringTable, $stringWhere , $option = array() ){
    	$tong = 0;
    	try {
    		// create string sql
    		$string = "";
    		if( !empty( $stringWhere ) ){
    			foreach ( $stringWhere as $key=>$where ){
    				if( $key == 0 ){
    					$string .= " WHERE $where ";
    				}else{
    					$string .= " and $where ";
    				}
    			}
    		}
    
    		$nameTable = strtolower($StringTable);
    		if( $option == null ){
    			$sql = " SELECT count(*) as tong FROM $nameTable $string ORDER BY id desc ";
    		} else{
    			$start = $option['start'];
    			$limit = $option['limit'];
    			$sql = " SELECT count(*) as tong FROM $nameTable $string ORDER BY id desc LIMIT $start,$limit ";
    		}
    
    		$sth = $this->getPdo()->prepare($sql);
    		$sth->execute();
    		$tong = $sth->fetch();
    		$tong = $tong['tong'];
    		return $tong;
    
    	} catch (Exception $e) {
    		echo $e->getMessage();
    	}
    	return $tong;
    }

}