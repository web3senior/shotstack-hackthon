<?php

class Errorfound extends Controller
{

  function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $this->view->title = 'صفحه پیدا نشد!';
    $this->view->render('error/index');
  }

}