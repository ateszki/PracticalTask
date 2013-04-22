<?php
ob_start();
session_start();
define("APPLICATION_ROOT","./");
include_once(APPLICATION_ROOT."config.php");
$contact = new contact();
if (!isset($_POST["action"])){
	die("Error");
} else {
	$action = $_POST["action"];
} 

if ($action=='save'){
	foreach ($_POST as $f => $v){
		$f = substr($f,2);
		$contact->$f = $v;
	}
	
	if ($contact->Id == ''){
		echo $contact->create();
	} else {
		echo $contact->update();
	}
}

if ($action=='delete'){
	$contact->Id = $_POST["F_Id"];
	echo $contact->delete();
}
?>