<?php
 ini_set('display_errors','On');
 error_reporting(E_ALL);

/**
 * USE THIS CLASS FOR SHOW SPECIFIC DATABASE
 */

class db {
  private $mysql_username;
  private $mysql_password;
  private $mysql_host;
  private $mysql_database;
  private $conn;
  
  function db(){
    
    
  }
  public function importConnect()
  {
    
        $docroot = $_SERVER['DOCUMENT_ROOT'];
        //echo "Docroot:$docroot";
        // LIVE DB CONNECTION SETTINGS
        //laptop Docroot = /var/www
        if(strstr($docroot,'/var/www/html/maps.marketart.com/current/'))
        //Mac Default Settings
        {
          $this->mysql_host = 'db1.marketart.com';
          $this->mysql_username = '471712_yah10';
          $this->mysql_password = 'P5XSTbDv';
          $this->mysql_database = 'maps';
        }
        else
        {
          $this->mysql_host = 'localhost';
          $this->mysql_username = 'root';
          $this->mysql_password = 'am1238wk';
          $this->mysql_database = 'maps';
        }
      $this->conn = mysql_connect($this->mysql_host, $this->mysql_username, $this->mysql_password)
       or die ("Could not connect: x " . mysql_error() ." ". $this->mysql_host);
    
    mysql_select_db($this->mysql_database);
    return $this->conn;

  }

  public function do_query($sql) {

  //echo "Do Query DB: $db <br>";
    
    $result = @mysql_query($sql);
    $total = @mysql_num_rows($result);
    if (@mysql_error() <> "") {     
        echo @mysql_error();
        $result = @mysql_list_processes();
        @mysql_free_result($result);
        die();
    }
    
    return $result;
}

 public function do_insert($sql) {
  //echo "Do Query DB: $db <br>"; 
  $result = @mysql_query($sql);
  $total = @mysql_num_rows($result);
  if (@mysql_error() <> "") {     
        echo @mysql_error();
        $result = @mysql_list_processes();
        @mysql_free_result($result);
        die();
    }
    
    return  mysql_insert_id();
}



  public function close(){
    mysql_close($this->conn);
  }
}