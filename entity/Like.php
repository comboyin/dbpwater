<?php
class Like {
	
	private $id;
	private $user_id;
	private $pictures_id;
	/**
	 * 
	 * @var User  */
	private $user;
	/**
	 * 
	 * @var Picture  */
	private $picture;
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getUserId() {
		return $this->user_id;
	}
	public function setUserId($user_id) {
		$this->user_id = $user_id;
		return $this;
	}
	public function getPicturesId() {
		return $this->pictures_id;
	}
	public function setPicturesId($pictures_id) {
		$this->pictures_id = $pictures_id;
		return $this;
	}
	public function getUser() {
		return $this->user;
	}
	public function setUser(User $user) {
		$this->user = $user;
		return $this;
	}
	public function getPicture() {
		return $this->picture;
	}
	public function setPicture(Picture $picture) {
		$this->picture = $picture;
		return $this;
	}
	
	
}