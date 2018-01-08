<?php
session_start();
try
{
	require_once "conf/startup.php";
	require_once "clsMySQL.php";
	
	$db = new clsMySQL($conf['db_host'], $conf['db_login'], $conf['db_pwd'], $conf['db_name']);
	$db->connect();
	Main::setDB($db);

	$router = new Router();
	$router->setControllersPath("controller");
	
	if (isset($_SESSION['id']) || $_GET['route'] == 'login' || $_GET['route'] == 'register') {
		$router->loadController($_GET['route']);
	} else {
		header('Location: /login');
		exit;
	}
	
} catch (Exception $e) { 
	include "lib/error.php";
	showException($e);
}