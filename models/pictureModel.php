<?php
class pictureModel extends baseModel{
	
	/**
	 * 
	 * @param Picture $pictures 
	 * @return null|array
	 * */
	public function addPicture( $pictures ){
		try {
			$this->getPdo()->beginTransaction();
			
			$is_error = null;
			
			foreach ( $pictures as $picture){
				/* @var $picture Picture */
				$url     = $picture->getUrl();
				$view    = $picture->getView();
				$like    = $picture->getLikeNumber();
				$date    = $picture->getRegistDatetime();
				$user_id = $picture->getUser()->getId();
				$sql     = " INSERT INTO picture ( url, `view`, like_number, regist_datetime, user_id ) 
						 VALUES ( '$url', $view, $like, '$date', '$user_id' ) ";
				
				$stmt = $this->getPdo()->prepare($sql);
				$stmt->execute();
			}
			
			$this->getPdo()->commit();
			
		} catch (Exception $e) {
			$is_error[] = $e->getMessage();
			$this->getPdo()->rollBack();
		}
		return $is_error;
	}
	
	
	/**
	 * delete picture
	 * @param string $pictures
	 * @return null|array
	 * */
	public function deletePictures( $idpicture ){
		try {
			$this->getPdo()->beginTransaction();
				
			$is_error = null;
			$likes = $this->listTableByWhere('Like', array( " pictures_id = $idpicture " ));
			/* @var $like Like */
			foreach ( $likes as $like ){
				// delete 
				$idlike = $like->getId();
				$error = $this->deleteTableByWhere('Like', " where id = '$idlike' ");
				if( $error != null ){
					utility::pushArrayToArray( $is_error['SQL'] , $error  );
				}
			}
			/* @var $picture Picture */
			$error = $this->deleteTableByWhere('picture', " where id = '$idpicture' ");
			if( $error != null ){
				utility::pushArrayToArray( $is_error['SQL'] , $error  );
			}
			$this->getPdo()->commit();
		} catch (Exception $e) {
			$is_error[] = $e->getMessage();
			$this->getPdo()->rollBack();
		}
		return $is_error;
	}
	
	/**
	 * get list picture
	 * @param string $where  */
	public function listPicture( $where ){
		$listObj = array();
		try {
			$sql = " SELECT * FROM picture $where ORDER BY regist_datetime desc ";
			$sth = $this->getPdo()->prepare($sql);
			$sth = $this->getPdo()->prepare($sql);
			$sth->setFetchMode( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE , 'Picture' );
			$sth->execute();
			/* @var $obj Picture  */
			$listObj = $sth->fetchAll();
			foreach ( $listObj as $obj ){
				$idPicture = $obj->getId();
				// get list like
				$likes = $this->listTableByWhere( 'Like' , array( " pictures_id = '$idPicture' " ));
				
				// set user from likes
				/* @var $like Like */
				/* @var $user User */
				
				foreach ( $likes as $like ){
					
					$user_id = $like->getUserId();
						
					// get user
					$users = $this->listTableByWhere( 'User' , array( " id = '$user_id' " ));
						
					$user = $users[0];
						
					$like->setUser($user);
				}
				
				
				$obj->setLike( $likes );
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		return $listObj;
	}
}