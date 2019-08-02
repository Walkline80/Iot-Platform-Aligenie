<?php
	require_once __DIR__ . '/server.php';
	require_once('../controller/server.php');

	// 天猫精灵平台发起的申请，返回code
	// https://walkline.wang/iot/authorize.php
	//		?redirect_uri=https%3A%2F%2Fopen.bot.tmall.com%2Foauth%2Fcallback%3FskillId%3D29830%26token%3DMTc3NDMyNjRBRkVISU5GRFZR
	//		&client_id=testclient
	//		&response_type=code
	//		&state=0.39731116525342836

	$request = \OAuth2\Request::createFromGlobals();
	$response = new \OAuth2\Response();


	// **** 这段去掉，不需要oauth2服务器进行校验 ****
	//
	// 验证 authorize request
	// 这里会验证client_id，redirect_uri等参数和client是否有scope
	// if (!$server->validateAuthorizeRequest($request, $response)) {
	// 	$response->send();
	// 	die;
	// }

	
	// 显示授权登录页面
	if (empty($_POST)) {
		exit(get_login_h5());
	}
	
	$is_authorized = true;
	$uuid = get_user_id($_POST);

	$server->handleAuthorizeRequest($request, $response, $is_authorized, $uuid);

	$response->send();
?>