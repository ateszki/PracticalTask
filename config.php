<?php
// DATA CONFIDENTIAL CONFIG
if(!defined("APPLICATION_ROOT")) die("Configuration Error on ".__FILE__);
define("APPLICATION_NAME","Practical Task");

define("CLASSES_FOLDER",APPLICATION_ROOT."classes/");


//LOAD CLASES
$dir = opendir(CLASSES_FOLDER);
while (false !== ($file = readdir($dir))) {
	if(($file != ".")&&($file != "..")) {
		include_once(CLASSES_FOLDER.$file);
	}
}

// DATABASE

$_DB_database = "PracticalTask";
$_DB_host = "localhost";
//$_DB_user = "root";
//$_DB_pass = "root";
$_DB_user = "PTaskUser";
$_DB_pass = "Ghte7u6gI33";
$_DB_port = "3306";


$MyDb = new Db($_DB_host,$_DB_user,$_DB_pass,$_DB_database,$_DB_port);

