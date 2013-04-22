<?php
if(!defined("APPLICATION_ROOT")) die("Configuration Error on ".__FILE__);

class Db {
	
	public $dbh;
	
	function Db($host,$user,$pass,$database,$port=0) {
		try {
			$this->dbh = new PDO("mysql:host=$host;port=$port;dbname=$database;", $user, $pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch(PDOException $ex) {
		    die("An Error occured! ".$ex->getMessage()); //user friendly message
		}		
	}
	
}