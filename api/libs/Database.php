<?php

class Database extends PDO
{

  function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_CHARSET, $DB_USER, $DB_PASS)
  {
    //parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS);
    parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET, $DB_USER, $DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * select
   * @param string $sql An SQL string
   * @param array $array Paramters to bind
   * @param constant $fetchMode A PDO fetch mode
   * @return mixed
   */
  function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
  {
    $sth = $this->prepare($sql);
    foreach ($array as $key => $value) {
      $sth->bindValue("$key", $value);
    }
    $sth->execute();
    return $sth->fetchAll($fetchMode);
  }

  /**
   * insert
   * @param string $table A name of table to insert into
   * @param string $data An associative array
   */
  function insert($table, $data)
  {
    try {
      ksort($data);
      $fielNames = implode('`, `', array_keys($data));
      $fildValues = ':' . implode(', :', array_keys($data));
      $sth = $this->prepare("INSERT INTO $table (`$fielNames`) VALUES ($fildValues)");
      foreach ($data as $key => $value) {
        $sth->bindValue(":$key", $value);
      }
      $sth->execute();
      return $this->lastInsertId();
    } catch (PDOExecption $e) {
      return $e->getMessage();
    }
  }

  /**
   * update
   * @param string $table A name of table to insert into
   * @param string $data An associative array
   * @param string $where the where query part
   */
  public function update($table, $data, $where)
  {
    ksort($data);
    $fieldDetails = NULL;
    foreach ($data as $key => $value) {
      $fieldDetails .= "`$key`=:$key,";
    }
    $fieldDetails = rtrim($fieldDetails, ',');
    $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
    foreach ($data as $key => $value) {
      $sth->bindValue(":$key", $value);
    }
    try {
      return $sth->execute();
    } catch (Exception $ex) {
      echo $ex;
      die;
    }
  }

  /**
   * delete
   * @param string $table
   * @param string $where
   * @param integer $limit
   * @return integer Affected Rows
   */
  public function delete($table, $where, $limit = 1)
  {
    try {
      return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    } catch (Exception $ex) {
      echo "<p style='color:Red;text-align: center'>";
      echo "این خطا مربوط به پایگاه داده می باشد، رکوردی که قصد حذف آن را دارید به داده ای در سایر قسمت ها وصل شده، لطفا ابتدا سایر داده ها رو بررسی کنید و سپس اقدام به حذف این رکورد نمایید";
      echo "</p><br/>";
      echo $ex->getMessage();
      die;
    }

    /*
      $sth = $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
      return $sth->execute();
     */
  }

}