<?php
abstract class baseController
{
    /*
     * @registry object
     */
    protected $registry;

    protected $model;

    protected $view;

    function __construct($registry)
    {
        $this->registry = $registry;

        $this->model = baseModel::getInstance($registry);

        $this->view = new view();

    }

    /**
     * @all controllers must contain an index method
     */
    abstract function index($arg=array());
	/**
	 *
	 * @return baseView  */
    public function getView(){

    	return $this->view;
    }

    protected function checkLogin(){
    	$result = false;
		if(isset($_SESSION['acl']['account'])){
			/* @var $account User */
			$account = $_SESSION['acl']['account'];

			if( $account->getGroup()->getLevel() > 0 ){
				$result = true;
			}
		}

		return $result;
    }

    protected function redirect($url){
		header("Location: ".__FOLDER.$url);
		exit(0);
    }

    protected function redirectUrl($url){
    	header("Location: " . $url);
    	exit(0);
    }

    /**
     * generate string url
     * @param array $option
     * @param string $arg
     * @return string  */
    public function url($option,$arg=null){
    	$moduleName = $option['module'];
    	if(!isset($option['controller'])){
    		$controllerName = 'index';
    	}else{
    		$controllerName = $option['controller'];
    	}
    	if(!isset($option['action'])){
    		$actionName = 'index';
    	}else{
    		$actionName = $option['action'];
    	}

    	$uri = __FOLDER . $moduleName.'/'.$controllerName.'/'.$actionName;
    	if($arg !=null){
    		foreach ($arg as $value){
    			$uri.='/'.$value;
    		}
    	}

    	return $uri;
    }
}
?>