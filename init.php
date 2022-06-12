<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
include 'admin/connect.php';
$sessionUser = '';
if(isset($_SESSION['user'])){
	$sessionUser = $_SESSION['user'];
}


  $tpl='includes/templates/';
include 'includes/functions/functions.php';
include 'includes/languages/english.php';
include $tpl . 'header.php';



