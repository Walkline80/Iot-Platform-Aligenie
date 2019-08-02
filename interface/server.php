<?php
	// 错误报告（这毕竟是一个演示！）
	ini_set('display_errors',1);error_reporting(E_ALL);

	// 自动加载
	require_once('OAuth2/Autoloader.php');
	require_once('../inc/config.php');
	OAuth2\Autoloader::register();

	$storage = new \OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $db_user, 'password' => $db_pass));

	// 通过存储对象或对象数组存储的oauth2服务器类
	$server = new \OAuth2\Server($storage);

	// 授权码 有效期只有30秒
	$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

	// 客户端证书  
	$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

	// 用户凭据
	$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
	// 刷新令牌  启用这个会报错，原因未知
	// $server->addGrantType(new OAuth2\GrantType\RefreshToken($refreshStorage))
?>