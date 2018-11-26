<?php
class pagination
{
    // guider
    // create $_config from option
    // then call function init -> param $_config
    private $_config = array(
        'current_page' => 1,
        'total_record' => 1, // total record
        'total_page' => 1, // total page
        'limit' => 10, // total record in page
        'start' => 0, // start record
        'link_full' => '', // link full: domain/page/{page}
        'link_first' => '', // link first page
        'range' => 9, // total button display
        'min' => 0, // Tham so min
        'max' => 0
    );

    public function __construct($config = null){
        if($config == null){
             $this->init($this->_config);
        }
        else {
            $this->init($config);
        }
    }
    /**
     *
     * @return the $_config
     */
    public function getConfig()
    {
        return $this->_config;
    }
    /**
     *
     * @param
     * multitype:number string $_config
     */
    public function setConfig($_config)
    {
        $this->_config = $_config;
    }
    // Tham so max, min va max la 2 tham so private  
    function init($config = array())
    {
        foreach ($config as $key => $val) {
            if (isset($this->_config[$key])) {
                $this->_config[$key] = $val;
            }
        }

        // check limit
        if ($this->_config['limit'] < 0) {
            $this->_config['limit'] = 0;
        }

        $this->_config['total_page'] = ceil($this->_config['total_record'] / $this->_config['limit']);

        // check total_page
        if (! $this->_config['total_page']) {
            $this->_config['total_page'] = 1;
        }

        // check current_page
        if ($this->_config['current_page'] < 1) {
            $this->_config['curremt_page'] = 1;
        }

        if ($this->_config['current_page'] > $this->_config['total_page']) {
            $this->_config['current_page'] = $this->_config['total_page'];
        }

        $this->_config['start'] = ($this->_config['current_page'] - 1) * $this->_config['limit'];

        $middle = ceil($this->_config['range'] / 2);

        if ($this->_config['total_page'] < $this->_config['range']) {
            $this->_config['min'] = 1;
            $this->_config['max'] = $this->_config['total_page'];
        }
        else{

            $this->_config['min'] = $this->_config['current_page'] - ($middle + 1);
            $this->_config['max'] = $this->_config['current_page'] + ($middle - 1);
            if( $this->_config['min'] < 1 ) {
                $this->_config['min'] = 1;
                $this->_config['max'] = $this->_config['range'];
            }
            else if( $this->_config['max'] > $this->_config['total_page'] ) {
                    $this->_config['max'] = $this->_config['total_page'];
                    $this->_config['min'] = $this->_config['total_page'] - $this->_config['range'] + 1;
            }

        }
    }

    private function __link($numPage)
    {
        if ($numPage <= 1 && $this->_config['link_first']) {
            return $this->_config['link_first'];
        }

        return str_replace('{page}', $numPage, $this->_config['link_full']);
    }

    // show pagination bootstrap
    public function html()
    {
        $html = '';

        /*
			<ul id="horizontal-list">
				<li>PAGE</li>
				<a href="#">
						<li class="border_li">«</li>
				</a>

				<a href="#">
						<li class="border_li active">1</li>
				</a>
				<a href="#">
						<li class="border_li">2</li>
				</a>
				<a href="#">
						<li class="border_li">3</li>
				</a>

				<a href="#">
						<li class="border_li">4</li>
				</a>
				<a href="#">
						<li class="border_li">5</li>
				</a>
				<a href="#">
						<li class="border_li">»</li>
				</a>
			</ul>
		 */
        if ($this->_config['total_record'] > $this->_config['limit']) {

            $html .= '<ul id="horizontal-list">';
            $html .= '<li>PAGE</li>';

            // button back
            if ($this->_config['current_page'] > 1) {
            	$html .= '<a href="' . $this->__link($this->_config['current_page'] - 1) . '"><li class="border_li">«</li></a>';
            }

            // page
            for ($i = $this->_config['min']; $i <= $this->_config['max']; $i ++) {

                if ($this->_config['current_page'] == $i) {
                	$html .= '<a href="' . $this->__link($i) . '"><li class="border_li active">'. $i .'</li></a>';
                } else {
                    $html .= '<a href="' . $this->__link($i) . '"><li class="border_li">'. $i .'</li></a>';
                }
            }

            // button next
            if ($this->_config['current_page'] != $this->_config['total_page']) {
            	$html .= '<a href="' . $this->__link($this->_config['current_page'] + 1) . '"><li class="border_li">»</li></a>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}