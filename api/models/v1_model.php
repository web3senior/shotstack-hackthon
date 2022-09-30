<?php

class V1_Model extends Model
{

  public function __construct()
  {
    parent::__construct();
  }

  function command($o, $tbl, $data = false, $id = false)
  {
    switch ($o) {
      case "count":
        return $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;");
      case "fetch":
        return $this->db->select("select * from `$tbl[0]`;");
      case "delete":
        return $this->db->delete("$tbl[0]", "`$tbl[1]`='$id'");
      case "insert":
        return $this->db->insert("$tbl[0]", $data);
      case "info":
        return $this->db->select("select * from `$tbl[0]` where `$tbl[1]`=:id", [':id' => $id]);
      case "update":
        return $this->db->update("$tbl[0]", $data, "`$tbl[1]`='{$id}'");
      default:
        die("O is unknown!");
    }
  }
  public function login($data)
  {
    $data = $this->db->select(
      'SELECT * FROM `admin` WHERE `email`=:email AND `password`=:password;',
      [':email' => $data['email'], ':password' => $data['password']]
    );
    if (is_array($data) && !empty($data))
      return $data[0];
    else
      return 0;
  }
  function subscribe($data)
  {
    $result = $this->db->select("select * from `subscription` where `email`=:email", [':email' =>  $data['email']]);
    if (count($result) < 1)
      return $this->command('insert', ['subscription', 'subscription_id'], $data);
    else
      return false;
  }

  function render($data)
  {
    $data = $this->command('insert', ['queue', 'queue_id'], $data);
    return $data;
  }
  function queue($email)
  {
    return  $this->db->select("select * from `queue` where `email`=:email", [':email' => $email]);
  }

  function template($id = false)
  {
    return $id ?
      $this->db->select("select * from `template` where `status`=:status and `template_id`=:id", [':status' => 1, ':id' => $id]) :
      $this->db->select("select * from `template` where `status`=:status", [':status' => 1]);
  }

  public function product_single($id)
  {
    $data = $this->db->select('select * from product WHERE `product_id`=:id;', array(':id' => $id));
    return $data;
  }
}
