<?php
class Friend_relation {
	
	private $id;
	private $user_id;
	private $user_id_to;
	private $regist_datetime;
	
	/**
	 * 
	 * @var User  */
	private $user;

	/**
	 *
	 * @var User  */
	private $user_to;
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param unknown_type $id        	
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getUserId() {
		return $this->user_id;
	}
	
	/**
	 *
	 * @param unknown_type $user_id        	
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 *
	 * @return User
	 */
	public function getUserIdTo() {
		return $this->user_id_to;
	}
	
	/**
	 *
	 * @param unknown_type $user_id_to        	
	 */
	public function setUserIdTo($user_id_to) {
		$this->user_id_to = $user_id_to;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRegistDatetime() {
		return $this->regist_datetime;
	}
	
	/**
	 *
	 * @param unknown_type $regist_datetime        	
	 */
	public function setRegistDatetime($regist_datetime) {
		$this->regist_datetime = $regist_datetime;
		return $this;
	}
	
	/**
	 *
	 * @return the User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 *
	 * @param User $user        	
	 */
	public function setUser(User $user) {
		$this->user = $user;
		return $this;
	}
	
	/**
	 *
	 * @return the User
	 */
	public function getUserTo() {
		return $this->user_to;
	}
	
	/**
	 *
	 * @param User $user_to        	
	 */
	public function setUserTo(User $user_to) {
		$this->user_to = $user_to;
		return $this;
	}
}