<?php
  function db_connect() {
    $dsn = 'mysql:host=localhost;dbname=making_blog;charset=utf8';
  	$user = 'root';
  	$password = '';

  	try{
  		$dbh = new PDO($dsn, $user, $password);
  		return $dbh;
  	}catch (PDOException $e){
  	    	print('Error:'.$e->getMessage());
  	    	die();
  	}
  }
 ?>
