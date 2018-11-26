<?php
class indexController extends baseController{

	public function index( $arg = array() ) {
		/* @var $userSession User */
		$userSession = $_SESSION['acl']['account'];
		/* @var $modelPicture PictureModel */
		$modelPicture = $this->model->get( 'Picture' );
		
		$idUserSession = $userSession->getId();
		
		$user = $modelPicture->listTableByWhere( 'User' , array( "id = $idUserSession" ));
		/* @var $user User  */
		$user = $user[0];
		
		// get pictures
		$pictures = $modelPicture->listPicture( " where user_id = '$idUserSession' " );
		$user->setPictures( $pictures );
		$user->setGroup( $userSession->getGroup() );

		// total friend
		
		$user->setTotalFriendList( $modelPicture->countListTableByWhere( 'friend_relation' , array( " user_id = $idUserSession or user_id_to = $idUserSession " )) );
		$user->setTotalFavorite( $modelPicture->countListTableByWhere( 'favorite' , array( " user_id = $idUserSession or user_id_to = $idUserSession" )) );
		// total favorite
		$this->getView()->content->user = $user;
		
	}
	/**
	 * get atrr value of user logined
	 * Lấy giá trị thuộc tính của user đã đăng nhập  */
	public function getValueParameterUserSession(){
		/* @var $userModel UserModel  */
		$userModel = $this->model->get('User');
		/* @var $accountSession User */
		$accountSession = $_SESSION['acl']['account'];
		$idAcc = $accountSession->getId();
		/*@var $acc User  */
		$acc = $userModel->listTableByWhere( 'User' , array( " id = $idAcc " ));
		$acc = $acc[0];
		$kq = array( 
			'fullname' => $acc->getFullname(),
				'email'=> $acc->getEmail(),
			'address'  => $acc->getAddress(),
			'sex'      => $acc->getSex(),
			'stringsex'	=> $acc->getStringSex(),
			'birthday' => $acc->getBirthday(),
		'introduction' => $acc->getIntroduction(),
		'username'     => $acc->getUsername()
		);
		echo json_encode( array(  'user' => $kq) );
		exit(0);
	}
	
	public function deletePicture(){
		$is_error = null;
		$idPicture = ( isset( $_POST['idpicture'] ) ) ? $_POST['idpicture'] : '';
		// validation
		/* @var $PictureModel PictureModel */
		/* @var $Picture Picture */
		$PictureModel = $this->model->get('Picture');
		$Picture = $PictureModel->listTableByWhere('Picture', array( " id = '$idPicture' " ));
		$cout = count($Picture);
		// exist
		if( $cout == 0 ){
			utility::pushArrayToArray( $is_error['picture'] , array( 'Picture not exist.' )) ;
		}else{
			$Picture = $Picture[0];
			// picture of user session
			/* @var $userSession User */
			$userSession = $_SESSION['acl']['account'];
			if(  $Picture->getUserId() != $userSession->getId() && $cout == 1){
				utility::pushArrayToArray( $is_error['picture'] , array( 'Picture not yours.' )) ;
			}
		}
		
		if( $is_error == null ){
			
			$error = $PictureModel->deletePictures($idPicture);
			
			if( $error != null ){
				// error
				utility::pushArrayToArray( $is_error['SQL'] , $error );
				
			}else if( $is_error == null ){
				
				$fileOld = $Picture->getUrl();
				utility::deleteFile(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$fileOld);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(
				array(
						'is_error' => $is_error
					 )
				);
		exit();
	}
	
	public function getHtmlListPicture(){
		/* @var $accountSession User */
		$accountSession = $_SESSION['acl']['account'];
		$idAcc = $accountSession->getId();
		
		$html = "";
		
		/* @var $PictureModel PictureModel */
		$PictureModel = $this->model->get('Picture');
		$pictures = $PictureModel->listPicture( " where user_id = $idAcc " );
		
		/* @var $picture Picture */
		foreach ( $pictures as $picture ){
			$is_like = $picture->is_like( $idAcc );
			$class_icon_thumbs = ( $is_like == false ) ? 'fa-thumbs-o-up' : 'fa-thumbs-o-down';
			$data_original_title = ( $is_like == false ) ? 'Like' : 'Unlike';
			$html .= '<div class="col-sm-3 shop-product">';
			$html .= '<div class="product-wrapper">';
			$html .= '<div class="product-image">';
			$html .= '<a href="">';
			$html .= '<img alt="" src="'. $picture->getLinkUrl() .'">';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '<div class="product-details">';
			$html .= '<div class="row">';
			$html .= '<div class="col-xs-12">';
			$html .= '<div class="product-tools">';
			$html .= '<a id-picture="'.$picture->getId().'" href="#" title="" data-toggle="tooltip" data-original-title="Delete">';
			$html .= '<i class="fa fa-remove"> | </i>';
			$html .= '</a>';
			$html .= '<a  href="#" title="" data-toggle="tooltip" data-original-title="View">'; 
			$html .= '<i class="fa fa-eye ">(' . $picture->getView() . ') |</i>'; 
			$html .= '</a>';
			$html .= '<a href="#" title="" data-toggle="tooltip" data-original-title="'.$data_original_title.'">';
			$html .= '<i class="fa '. $class_icon_thumbs .' ">(' . $picture->getLikeNumber() . ')</i>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		header('Content-Type: application/json');
		echo json_encode(
				array(
					'content' => $html	
					)
				);
		exit();
	}
	
	public function addPictures(){
		
		$is_error = null;
		$listPicture = isset($_FILES['pictures']) ? $_FILES['pictures'] : array();
		
		$coutListImage = isset($listPicture['name']) ? count($listPicture['name']) : 0;
		$listImageCustom = array();
		for($i = 0 ; $i < $coutListImage ; $i++){
			$listImageCustom[$i]['name'] = $listPicture['name'][$i];
			$listImageCustom[$i]['type'] = $listPicture['type'][$i];
			$listImageCustom[$i]['tmp_name'] = $listPicture['tmp_name'][$i];
			$listImageCustom[$i]['error'] = $listPicture['error'][$i];
			$listImageCustom[$i]['size'] = $listPicture['size'][$i];
		}
		
		$valid = new validation();
		
		// list image
		foreach ( $listImageCustom as $imageCustom ){
			if( ( $errorImageCustom = $valid->fileImageValidation( $imageCustom , array( 'size' => 710000 ) ) ) != null){
				if( is_array( $errorImageCustom ) ){
					utility::pushArrayToArray( $is_error['List Pictures'][$imageCustom['name']] , $errorImageCustom);
				}else if( is_string( $errorImageCustom ) ){
					$listImageFileName[] = $errorImageCustom;
				}
			}
		}
		
		if( !isset( $listImageFileName )  ){
			utility::pushArrayToArray( $is_error['List Pictures'], array( 'Not find image selected.' ));
		}
		
		if( $is_error == null ){
			
			// validation success
			$pictures = array();
			/* @var $userSession User */
			$userSession = $_SESSION['acl']['account'];
			
			foreach ( $listImageFileName as $ImageFileName ){
				$picture = new Picture();
				$picture->setUrl( $ImageFileName );
				$picture->setView(0);
				$picture->setLikeNumber(0);
				$now = new DateTime();
				$s = $now->format('Y-m-d h:i:s');
				$picture->setRegistDatetime( $s );
				$picture->setUser( $userSession );
				$pictures[] = $picture;
			}
			
			/* @var $pictureModel PictureModel */
			$pictureModel = $this->model->get('Picture');
			$error = $pictureModel->addPicture( $pictures );
			
			if( $error != null ){
				utility::pushArrayToArray( $is_error['SQL'] , $error );
			}
						
		}
		
		if( $is_error != null ){
			
			// delete file
			if( isset( $listImageFileName ) ){
				foreach ( $listImageFileName as $imageFileName ){
					utility::deleteFile(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$imageFileName);
				}
			}	
		}
		header('Content-Type: application/json');
		echo json_encode( array(
				'is_error' => $is_error
		));
		exit(0);
		
	}
	
	public function changeAvatar(){
		$is_error = null;
		$avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : array();
		// validation
		
		$valid = new validation();
		// avatar
		if( ( $errorImage = $valid->fileImageValidation( $avatar , array( 'size' => 710000) ) ) != null){
			if(is_array($errorImage)){
				utility::pushArrayToArray( $is_error['avatar'] , $errorImage);
			}else if( is_string( $errorImage ) ){
				$imageFileName = $errorImage;
			}
		}
		
		if( $is_error == null ){
			/* @var $accountSession User */
			$accountSession = $_SESSION['acl']['account'];
			$idAcc = $accountSession->getId();
			/* @var $userModel UserModel */
			$userModel = $this->model->get('User');
			// delete avatar old
			$acc = $userModel->listTableByWhere( 'User' , array( "id = $idAcc" ));
			$acc = $acc[0];
			$fileOld = $acc->getAvatar();
			
			$setValue = " set avatar = '$imageFileName' ";
			$tableName = " user ";
			
			$stringWhere = " where id = $idAcc ";
			$error = $userModel->updateTableByWhere($tableName, $setValue, $stringWhere);
			
			if( $error != null ){
				// error
				utility::pushArrayToArray($is_error['SQL'], $error);
				if(file_exists(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$imageFileName)){
					unlink(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$imageFileName);
				}
			}else{
				if(file_exists(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$fileOld)){
					unlink(__SITE_PATH.'/'.__FOLDER_UPLOADS.'/'.$fileOld);
				}
				// update session
				$users = $userModel->listTableByWhere( 'User', array(" id = '$idAcc' "));
				/* @var $user User */
				$user = $users[0];
				$group_id = $user->getGroupId();
				$group = $userModel->listTableByWhere( 'Group' , array( " id = '$group_id' " ));
				$user->setGroup( $group[0] );
				$_SESSION['acl']['account'] = $users[0];
			}
		}
		// $is_error == null  => success
		// $is_error == array => error
		header('Content-Type: application/json');
		echo json_encode(
				array(
					'is_error' => $is_error )
				);
		exit(0);
	}
	
	public function friendList(){
		/* @var $usersession User */
		$usersession = $_SESSION['acl']['account'];
		/* @var $friendRelationModel FriendrelationModel */
		$friendRelationModel  = $this->model->get('Friendrelation');
		$friendRelations      = $friendRelationModel->getListFriendRelation( $usersession->getId() );
		
		
		$idUserSession = $usersession->getId();
		
		$model = $this->model->get('Picture');
		$totalFriend   =  $model->countListTableByWhere( 'friend_relation' , array( " user_id = $idUserSession or user_id_to = $idUserSession " )) ;
		$totalFavorite =  $model->countListTableByWhere( 'favorite' , array( " user_id = $idUserSession or user_id_to = $idUserSession" ));
		
		$this->getView()->content->totalFriend     = $totalFriend;
		$this->getView()->content->totalFavorite   = $totalFavorite;
		$this->getView()->content->friendRelations = $friendRelations;
		
	}
	
	public function editProfile(){
		$is_error = null;
		$stringSetValue = "";
		// get parameter.
		
		// fullname
		$fullname = isset( $_POST['fullname'] ) ? $_POST['fullname'] : null;
		// email
		$email    = isset( $_POST['email'] ) ? $_POST['email'] : null;
		// sex
		$sex      = isset( $_POST['sex'] ) ? $_POST['sex'] : null;
		// birthday
		$birthday = isset( $_POST['birthday'] ) ? $_POST['birthday'] : null;
		// address
		$address  = isset( $_POST['address'] ) ? $_POST['address'] : null;
		
		// validation
		$valid = new validation();
		// fullname
		if( $fullname != null ){
			
			$stringSetValue .= " fullname = '$fullname' , " ;
			
			// empty
			if( ( $emptyFullName = $valid->checkEmpty( $fullname ) ) != null )
			{
				utility::pushArrayToArray( $is_error['Full Name'] , $emptyFullName);
			}
			
			// between
			if( ( $betweenFullName = $valid->between( $fullname, array( 'min' => 4 , 'max' => 30 ) )) != null )
			{
				utility::pushArrayToArray( $is_error['Full Name'] , $betweenFullName);
			}
		}
		
		// email 
		if( $email != null ){
			
			$stringSetValue .= " email = '$email' , ";
			// empty
			if( ( $emptyEmail = $valid->checkEmpty( $email ) )  != null )
			{
				utility::pushArrayToArray( $is_error['Email'] , $emptyEmail);
			}
			
			// between
			if( ( $betweenEmail = $valid->between( $email, array( 'min' => 6 , 'max' => 40 ) ) ) != null )
			{
				utility::pushArrayToArray( $is_error['Email'] , $betweenEmail);
			}
			
			// email
			if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				utility::pushArrayToArray( $is_error['Email'] , array( "This ($email) email address is NOT considered valid." ) );
			}
		}
		// sex
		if( $sex != null ){
			
			$stringSetValue .= " sex = $sex , ";
			// empty
			if( ( $emptySex = $valid->checkEmpty( $sex ) )  != null )
			{
				utility::pushArrayToArray( $is_error['Sex'] , $emptySex);
			}
			
			// value 
			if( $sex != 0 && $sex != 1 ){
				utility::pushArrayToArray( $is_error['Sex'] , array( "Value is NOT considered valid." ));
			}
		}
		
		// birthday 
		if( $birthday != null ){
			$stringSetValue .= " birthday = '$birthday' , ";
			// birthday
	        $split = array();
	        if( ( $errorBirthdayEmpty = $valid->checkEmpty( $birthday ) ) != null){
	            utility::pushArrayToArray($is_error['birthday'], $errorBirthdayEmpty);
	        }
	        if ( !preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $birthday , $split) || !checkdate( $split[2], $split[3], $split[1] ) )
	        {
	            utility::pushArrayToArray($is_error['birthday'], array( 'Birthday format yyyy-mm-dd.' ) );
	            utility::pushArrayToArray($is_error['birthday'], array( 'Value birthdat is not DATE.' ) );
	        }
		}
		
		if( $address != null ){
			
			$stringSetValue .= " address = '$address' , ";
			// address
			if( ( $errorAddressEmpty = $valid->checkEmpty( $address ) ) != null){
				utility::pushArrayToArray($is_error['address'], $errorAddressEmpty);
			}
			if(($errorAddress = $valid->between( $address, array('min'=>8, 'max'=>100) )) != null){
				utility::pushArrayToArray($is_error['address'], $errorAddress);
			}
		}
		
		if( strlen( $stringSetValue ) == 0 ){
			utility::pushArrayToArray($is_error['Parameter'], array( 'No parameter.' ));
		}
		
		if( $is_error == null && strlen( $stringSetValue ) > 0){
			// update database
			$stringSetValue = " set " . $stringSetValue;
			$stringSetValue = trim( $stringSetValue );
			$stringSetValue = substr_replace($stringSetValue, ' ', strlen($stringSetValue)-1);
			$tableName      = " user "    ;
			/* @var $accountSession User */
			$accountSession = $_SESSION['acl']['account'];
			$idAcc = $accountSession->getId();
			
			$stringWhere    = " where id = '$idAcc' "  ;
			
			/* @var $modelUser UserModel  */
			$modelUser = $this->model->get( 'User' );
			$error = $modelUser->updateTableByWhere($tableName, $stringSetValue, $stringWhere);
			if( $error != null ){
				utility::pushArrayToArray($is_error['SQL'],  $error );
			}else{
				// update session
				$users = $modelUser->listTableByWhere( 'User', array(" id = '$idAcc' "));
				/* @var $user User */
				$user = $users[0];
				$group_id = $user->getGroupId();
				$group = $modelUser->listTableByWhere( 'Group' , array( " id = '$group_id' " ));
				$user->setGroup( $group[0] );
				$_SESSION['acl']['account'] = $users[0];
				
			}
		}
		// $is_error == null  => success
		// $is_error == array => error
		header('Content-Type: application/json');
		echo json_encode( 
				array( 
					'is_error' => $is_error ) 
				);
		
		exit(0);
	}
	
	public function unfriend(){
		$is_error = null;
		$userSession = $this->getUserSession();
		$userID = ( isset($_POST['UserId']) ) ? $_POST['UserId'] : '';
		/* @var $UserModel UserModel */
		/* @var $FriendModel FriendrelationModel */
		$UserModel = $this->model->get('User');
		$FriendModel = $this->model->get('Friendrelation');
		$Users = $UserModel->listTableByWhere( 'User', array( " id = '$userID' " ));
		if( count( $Users ) == 0 ){
			utility::pushArrayToArray( $is_error['User'] , array( "User have id '$userID' not exist." ));
		}else{
			// database
			//$FriendModel->findFriendRelation($userSession->getId(), $userID);
			$error = $FriendModel->deleteFriendRelation( $userSession->getId() , $userID);
			if( $error != null ){
				utility::pushArrayToArray( $is_error['User'] , $error);
			}
		}
		// $is_error == null  => success
		// $is_error == array => error
		header('Content-Type: application/json');
		echo json_encode(
				array(
					'is_error' => $is_error )
				);
		
		exit(0);
	}
	/**
	 * @return User  */
	private function getUserSession(){
		$user = $_SESSION['acl']['account'];
		return $user;
	}
	
	public function getHTMLListFriendRelation(){
		$html = "";
		$user = $this->getUserSession();
		/* @var $friendRelationModel FriendrelationModel */
		$friendRelationModel = $this->model->get('friendrelation');
		$friendRelations = $friendRelationModel->getListFriendRelation( $user->getId() );
		$totalFriend     = count( $friendRelations );
		/* @var $friendRelation Friend_relation */
		foreach ( $friendRelations as $friendRelation ){
			$html .= '<div class="col-xs-6">';
			$html .= '<div class="list-friend">';
			$html .= '<div class="media">';
			$html .= '<p class="pull-left">';
			$html .= '<a idfriend="'.$friendRelation->getUserTo()->getId().'" href="/lession3/user/action/profile/'.$friendRelation->getUserTo()->getUsername().'">'.$friendRelation->getUserTo()->getFullname().'</a>';
			$html .= '</p>';
			$html .= '<a class="pull-left" href="#"> <img class="media-object" src="'.$friendRelation->getUserTo()->getLinkAvatar().'" alt="">';
			$html .= '</a>';
			$html .= '<p class="pull-left">';
			$html .= '<a class="Unfriend" href="#">Unfriend</a>';
			$html .= '</p>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		header('Content-Type: application/json');
		echo json_encode(
				array(
						'content' => $html,
						'totalFriend'   => $totalFriend
					 )
				);
		exit();
	}
	

}