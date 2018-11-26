<?php
abstract class baseView{
	protected $_template = 'layout/defaultLayout';
	protected $_properties = array ();

	public function __construct($template = '', array $data = array()) {

		if ($template !== '') {
			$this->setTemplate ($template);
		}
		if (!empty($data)) {
			foreach ($data as $name => $value) {
				$this->$name = $value;
			}
		}

	}
	/**
	 * set a new view template
	 * @param unknown $template
	 * @throws ViewException  */
	public function setTemplate($template) {

		$template = __SITE_PATH . '/' .$template . '.php';


		if (! file_exists ( $template )) {
            echo $template;
            exit;
			throw new ViewException ( 'The specified view template does not exist.' );
		}
		$this->_template = $template;
	}

	/**
	 * get the view template
	 * @return string  */
	public function getTemplate() {
		return $this->_template;
	}

	/**
	 * set a new property for the view
	 * @param unknown $name
	 * @param unknown $value  */
	public function __set($name, $value) {
		$this->_properties [$name] = $value;
	}


	/**
	 * get the specified property from the view
	 * @param string $name
	 * @throws ViewException
	 * @return multitype:  */
	public function __get($name) {
		if (! isset ( $this->_properties [$name] )) {
			throw new ViewException ( 'The requested property is not valid for this view.' );
		}
		return $this->_properties [$name];
	}

	/**
	 * remove the specified property from the view
	 * @param unknown $name  */
	public function __unset($name) {
		if (isset ( $this->_properties [$name] )) {
			unset ( $this->_properties [$name] );
		}
	}

	/**
	 * add a new view (implemented by view subclasses)
	 * @param AbstractView $view  */
	abstract public function addView(AbstractView $view);

	// remove a view (implemented by view subclasses)
	abstract public function removeView(AbstractView $view);

	// render the view template
	public function render() {
		$path = $this->_template;

		if ($this->_template !== '') {
			extract($this->_properties);
			ob_start ();
			include ($path);
			return ob_get_clean ();
		}
	}

	public function getProperties(){
		return $this->_properties;
	}
}

class ViewException extends Exception{}