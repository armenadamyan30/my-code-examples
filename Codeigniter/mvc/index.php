<?php
	session_start();
	require_once 'config.php';
	require_once 'controllers/bootstrap.php' ;
	require_once 'controllers/controller.php' ;

	//$controller = new Controller();
	//$controller->start();
	$bootstrap = new Bootstrap();
	$bootstrap->start();
?>


