<?php

ini_set('session.gc_maxlifetime', 86400);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

session_start();

include("inc/global_vars.php");
include("inc/functions.php");

// ini_set('error_reporting', E_ALL); 

$a = $_GET['a'];

switch ($a)
{
	case "test":
		test();
		break;
	
	case "settings_update":
		settings_update();
		break;
			
// default
				
	default:
		home();
		break;
}

function home(){
	die('access denied to function name ' . $_GET['a']);
}

function test(){
	echo '<h3>$_SESSION</h3>';
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_POST</h3>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_GET</h3>';
	echo '<pre>';
	print_r($_GET);
	echo '</pre>';
	echo '<hr>';
}

function set_status_message(){
	$status 				= $_GET['status'];
	$message			= $_GET['message'];
	
	status_message($status, $message);
}

function settings_update()
{
	$api_key = $_POST['api_key'];

	$file = "<?php\n\n\$config['api_key'] = '$api_key';\n\n?>";

	file_put_contents('/zeus/controller/global_vars.php', $file);
	file_put_contents('/zeus/global_vars.php', $file);

	go($_SERVER['HTTP_REFERER']);
}