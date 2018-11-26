<?php

class Partial extends baseView
{
    // throw an exception as a partial view (leaf) cannot add another view
    public function addView(AbstractView $view)
    {
        throw new ViewException('A partial view cannot add another view.');
    }

    // throw an exception as a partial view (leaf) cannot remove another view
    public function removeView(AbstractView $view)
    {
        throw new ViewException('A partial view cannot remove another view.');
    }
}