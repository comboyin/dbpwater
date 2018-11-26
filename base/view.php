<?php
class view extends baseView
{
    protected $_views = array();

    // add a new view object
    public function addView(AbstractView $view)
    {
        if (!in_array($view, $this->_views, TRUE)) {
            $this->_views[] = $view;
        }
        return $this;
    }

    // remove an existing view object
    public function removeView(AbstractView $view)
    {
        if (in_array($view, $this->_views, TRUE)) {
            $views = array();
            foreach ($this->_views as $_view) {
                if ($_view !== $view) {
                    $views[] = $_view;
                }
            }
            $this->_views = $views;
        }
        return $this;
    }

    // render each partial view (leaf) and optionally the composite view
    public function render()
    {
        $innerView = '';
        if (!empty($this->_views)) {
            foreach ($this->_views as $view) {
                $innerView .= $view->render();
            }
            $this->content = $innerView;
        }
        $compositeView = parent::render();
        return !empty($compositeView) ? $compositeView : $innerView;
    }
}// End View class