<?php
	// include our OAuth2 Server object
	require_once __DIR__ . '/server.php';

	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
?>