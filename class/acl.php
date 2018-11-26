<?php

class acl {

	private $_config;
	/**
	 * @var router
	 * */
	public $_router;


	public function __construct( $config, $router ){
		$this->_config = $config;
		$this->_router = $router;
		$this->init();
	}

	private function init(){
		// start session
		$session = session_id();
		if(empty($session)){
			session_start();
		}

		$moduleName = $this->_router->module;
		$controllerName = $this->_router->controller;
		$actionName = $this->_router->action;

		if(!isset($_SESSION['acl']['account'])){
			$accountTemp = new User();
			$_SESSION['acl']['account'] = $accountTemp;
		}

		/* @var $account User */
		$account = $_SESSION['acl']['account'];


		// check login form backend module
		if( $moduleName == 'user' ){
			if( $account->getGroup()->getLevel() <= 0){
				// redirect to login controller
				$this->redirect(
					$this->_router->url(
						array(

							'module' => 'login'

						)
					)
				);
			}
		}

		if($moduleName == 'error' && $controllerName == 'error404'){
			return;
		}
		// check pemission account
		else if( !$this->checkPermission($moduleName, $controllerName, $actionName, $account->getGroup()->getLevel() )){
			// redictect to deny page
			$this->redirect(
					$this->_router->url(
							array(
								'module' => 'error',
								'controller' => 'deny',
								'action' => 'index'
							)
					)
			);
		}
	}

	/**
	 * Check permission
	 * @param string $moduleName
	 * @param string $controllerName
	 * @param string $action
	 * @param string $type
	 * @return bool*/
	public function checkPermission( $moduleName, $controllerName, $action, $type){

		$config = $this->_config;
		$allow = $config['allow'];
		$deny = $config['deny'];
		$result = false;


		// check allow
		if(isset($allow[$moduleName][$controllerName])){

			$permissionController = $allow[$moduleName][$controllerName];
			// if isset "all" (all action)
			if(isset($permissionController['all'])){

				if($permissionController['all'] == 'all'){

					$result = true;
				}else if(in_array($type, $permissionController['all'])){

					$result = true;
				}
			}else if(isset($permissionController[$action])){
				if($permissionController[$action] == 'all'){
					$result = true;
				}else if(in_array($type, $permissionController[$action])){
					$result = true;
				}
			}
		}

		//check deny
		if(isset($deny[$moduleName][$controllerName])){
			$permissionController = $deny[$moduleName][$controllerName];
			// if isset "all" (all action)
			if(isset($permissionController['all'])){
				if($permissionController['all'] == 'all'){
					$result = false;
				}else if(in_array($type, $permissionController['all'])){
					$result = false;
				}
			}else if(isset($permissionController[$action])){
				if($permissionController[$action] == 'all'){
					$result = false;
				}else if(in_array($type, $permissionController[$action])){
					$result = false;
				}
			}
		}

		return $result;
	}

	public function redirect( $url ){
		header("Location: ".$url);
		exit(0);
	}
}