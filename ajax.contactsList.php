<?php
ob_start();
session_start();
define("APPLICATION_ROOT","./");
include_once(APPLICATION_ROOT."config.php");
$contactManager = new contactList();
if (!isset($_GET["page"])){
	$page = 1;
} else {
	$page = $_GET["page"];
}
if (!isset($_GET["rows"])){
	$rows = 10;
} else {
	$rows = $_GET["rows"];
}
if (!isset($_GET["orderby"])){
	$orderby = 'Id';
} else {
	$orderby = $_GET["orderby"];
}
echo json_encode($contactManager->listPage($page,$rows,$orderby));
?>