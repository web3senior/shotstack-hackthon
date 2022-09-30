<?php

class Model
{

  function __construct()
  {
    $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_CHARSET, DB_USER, DB_PASS);
    // works not with the following set to 0. You can comment this line as 1 is default
  }

}