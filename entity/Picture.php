<?php
class Picture {
	
	private $id;
	private $url;
	private $view;
	private $like_number;
	private $regist_datetime;
	private $user_id;
	
	/**
	 * 
	 * @var Like  */
	private $like;
	
	/**
	 * @var User 
	 * */
	private $user;
	
	public function __construct(){
		$this->user = new User();
	}
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getUrl() {
		return $this->url;
	}
	public function getLinkUrl() {
		$string = __FOLDER . __FOLDER_UPLOADS . '/' . $this->url;
		return $string;
	}
	
	public function getViewUrl(){
		return __FOLDER . __FOLDER_UPLOADS . '/' . $this->url;
	}
	
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}
	public function getView() {
		return $this->view;
	}
	public function setView($view) {
		$this->view = $view;
		return $this;
	}
	public function getLikeNumber() {
		return $this->like_number;
	}
	public function setLikeNumber($like_number) {
		$this->like_number = $like_number;
		return $this;
	}
	public function getRegistDatetime() {
		return $this->regist_datetime;
	}
	public function setRegistDatetime($regist_datetime) {
		$this->regist_datetime = $regist_datetime;
		return $this;
	}
	public function getUserId() {
		return $this->user_id;
	}
	public function setUserId($user_id) {
		$this->user_id = $user_id;
		return $this;
	}
	public function getUser() {
		return $this->user;
	}
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
	public function getLike() {
		return $this->like;
	}
	public function setLike($like) {
		$this->like = $like;
		return $this;
	}
	
	public function is_like( $iduser ){
		$is_like = false;
		/* @var $like Like  */
		foreach ( $this->like as $like ){
			if( $like->getUser()->getId() == $iduser ){
				$is_like = true;
			}
		}
		return $is_like;
	}
}