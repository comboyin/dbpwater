<?php
class userModel extends baseModel{
	
	/**
	 *
	 * @param string $user
	 * @param string $pass
	 * @return boolean|account  */
    public function checkLogin($user,$pass){
    	try {
    		$passMD5 = md5($pass);
    		$sql = "select * from user where username = '$user' and password = '$passMD5'";
    		$stmt = $this->getPdo()->prepare ( $sql );
    		$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
    		$stmt->execute();
    		/* @var $result User */
    		$result = $stmt->fetch();
    				
    		if( $result  !== false ){
    			$idGroup = $result->getGroupId();
				$sqlGroup = "select * from `group` where `group`.id = $idGroup";
				$stmt = $this->getPdo()->prepare ( $sqlGroup );
				$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Group');
				$stmt->execute();
				$resultGroup = $stmt->fetch();
				$result->setGroup( $resultGroup );
				
				$id_user = $result->getId();
				// get list picture
				$pictures = $this->listTableByWhere( 'Picture' , array( "user_id = $id_user" ));
				
				$result->setPictures( $pictures );
				
    		}
			return $result;
    	} catch (Exception $e) {
    		return false;
    	}

    }



}