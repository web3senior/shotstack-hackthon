<?php

class View
{
  public  $title;
  public $data;

  public function render($name, $noInclude = false)
  {
    require './views/' . $name . '.php';
  }
}