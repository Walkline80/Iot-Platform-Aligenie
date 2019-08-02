<?php
	require_once('../inc/config.php');

	$mysqli = new mysqli($db_host, $db_user, $db_pass);

	if ($mysqli->connect_errno) {
		die("连接数据库失败：" . $mysqli->connect_error);
	}

	$mysqli->query("set names utf8");
	$mysqli->query("set character set utf8");
	$mysqli->select_db($db_name);

	// 设置默认时区
	date_default_timezone_set("Asia/Shanghai");
?>