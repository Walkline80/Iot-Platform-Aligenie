<?php
	// include our OAuth2 Server object
	require_once __DIR__ . '/server.php';
	require_once('../inc/function.php');

	logger("token", file_get_contents('php://input'));
	
	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
?>