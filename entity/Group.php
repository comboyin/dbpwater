<?php
class Group {
	private $id;
	private $level;
	private $name;
	/**
	 *
	 * @var DateTime */
	private $regist_datetime;
	/**
	 * @var array */
	private $listuser;


	public function __construct(){
		$this->level = 0;
	}

	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getLevel() {
		return $this->level;
	}
	
	public function getStringLevel() {
		if( $this->level == 1 ){
			return 'Admin';
		}else if( $this->level == 2 ){
			return 'Operator';
		}else if( $this->level == 3 ){
			return 'User';
		}
	}
	
	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getRegistDatetime() {
		return $this->regist_datetime;
	}
	public function setRegistDatetime(DateTime $regist_datetime) {
		$this->regist_datetime = $regist_datetime;
		return $this;
	}
	public function getListuser() {
		return $this->listuser;
	}
	public function setListuser(array $listuser) {
		$this->listuser = $listuser;
		return $this;
	}




}