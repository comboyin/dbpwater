<?php
class friendrelationModel extends baseModel{
	
	public function getListFriendRelation( $user_id ){
		$ListFriendRelation = array();
		try {
			$ListFriendRelation = $this->listTableByWhere( 'Friend_relation' , array( " user_id = '$user_id' or user_id_to = '$user_id' " ));
			/* @var $FriendRelation Friend_relation */
			foreach ( $ListFriendRelation as $FriendRelation ){
				// get friend 
				if( $FriendRelation->getUserIdTo() == $user_id ){
					$tempidto   = $FriendRelation->getUserIdTo();
					$tempid     = $FriendRelation->getUserId();
					$FriendRelation->setUserIdTo($tempid);
					$FriendRelation->getUserId($tempidto);
				}
				$users = $this->listTableByWhere( 'User' , array( " id = '$user_id' " ));
				$user_id_to = $FriendRelation->getUserIdTo();
				$users_to = $this->listTableByWhere( 'User' , array( " id = '$user_id_to' " ));
				$FriendRelation->setUser( $users[0] );
				$FriendRelation->setUserTo( $users_to[0] );	
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		return $ListFriendRelation;
	}
	/**
	 * check A and B is friend order
	 * @param unknown $user_id
	 * @param unknown $user_id_to  
	 * @return bool*/
	public function checkFriendRelation( $user_id , $user_id_to ){
		
		try {
			$listFriendRelation = $this->getListFriendRelation( $user_id );
			
			/* @var $value Friend_relation */
			foreach ($listFriendRelation as $value) {
				if( $value->getUserTo()->getId() == $user_id_to ){
					return true;
				}
			}
		}catch (Exception $e) {
			echo $e->getMessage();
		}
		return false;
	}
	
	public function findFriendRelation( $user_id, $user_id_to ){
		$id = -1;
		$sql = " select id from friend_relation where user_id = '$user_id' and user_id_to = '$user_id_to' ";
		$stmt = $this->getPdo()->prepare( $sql );
		$stmt->execute();
		$rs = $stmt->fetch();
		if ( isset($rs['id']) ){
			$id = $rs['id'];
		}else{
			$sql = " select id from friend_relation where user_id = '$user_id_to' and user_id_to = '$user_id' ";
			$stmt = $this->getPdo()->prepare( $sql );
			$stmt->execute();
			$rs = $stmt->fetch();
			if( isset($rs['id']) ){
				$id = $rs['id'];
			}
		}
		return $id;
	}
	
	public function deleteFriendRelation( $user_id, $user_id_to ){
		$is_error = null;
		try{
			if( !$this->checkFriendRelation( $user_id, $user_id_to ) ){
				$is_error[] = "User have id '$user_id_to' not friend relation.";
			}else{
				// find 
				$idFriendRelation = $this->findFriendRelation($user_id, $user_id_to);
				
				$error = $this->deleteTableByWhere( 'friend_relation' , " where id = '$idFriendRelation' ");
				
				if( $error != null ){
					utility::pushArrayToArray( $is_error , $error);
				}
			}
		}catch (Exception $e) {
			echo $e->getMessage();
			$is_error[] = $e->getMessage();
		}
		return $is_error;
	}
}