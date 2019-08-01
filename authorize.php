<?php
	require_once __DIR__ . '/server.php';

	// 天猫精灵平台发起的申请，返回code
	// https://walkline.wang/iot/authorize.php
	//		?redirect_uri=https%3A%2F%2Fopen.bot.tmall.com%2Foauth%2Fcallback%3FskillId%3D29830%26token%3DMTc3NDMyNjRBRkVISU5GRFZR
	//		&client_id=testclient
	//		&response_type=code
	//		&state=0.39731116525342836

	$request = \OAuth2\Request::createFromGlobals();
	$response = new \OAuth2\Response();


	// **** 这段去掉，不需要oauth2服务器进行校验 ****

	// 验证 authorize request
	// 这里会验证client_id，redirect_uri等参数和client是否有scope
	// if (!$server->validateAuthorizeRequest($request, $response)) {
	// 	$response->send();
	// 	die;
	// }

	
	// 显示授权登录页面
	// if (empty($_POST)) {
	// 	//获取client类型的storage
	// 	//不过这里我们在server里设置了storage，其实都是一样的storage->pdo.mysql
	// 	$pdo = $server->getStorage('client');
	// 	//获取oauth_clients表的对应的client应用的数据
	// 	$clientInfo = $pdo->getClientDetails($request->query('client_id'));
	// 	var_dump($clientInfo);
	// 	$this->assign('clientInfo', $clientInfo);
	// 	$this->display('authorize');
	// 	die();
	// }
	
	// if (empty($_POST)) {
	// 	exit('
	// 	<form method="post">
	// 	 <label>是否授权给?</label><br />
	// 	 <input type="submit" name="authorized" value="yes">
	// 	 <input type="submit" name="authorized" value="no">
	// 	 </form>  
	// 	 <a href="/login.php?logout=1">退出登录</a>');
	// } else {
	// 	echo "fail";
	// }

	
	$is_authorized = true;
	// // 当然这部分常规是基于自己现有的帐号系统验证
	// if (!$uid = $this->checkLogin($request)) {
	// 	$is_authorized = false;
	// }
	


	// 这里是授权获取code，并拼接Location地址返回相应
	// Location的地址类似：http://sxx.qkl.local/v2/oauth/cb?code=69d78ea06b5ee41acbb9dfb90500823c8ac0241d&state=xyz
	// 这里的$uid不是上面oauth_users表的uid, 是自己系统里的帐号的id，你也可以省略该参数
	$server->handleAuthorizeRequest($request, $response, $is_authorized, "111"); // $uid);

//        if ($is_authorized) {
//            // 这里会创建Location跳转，你可以直接获取相关的跳转url，用于debug
        //    $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
        //    exit("SUCCESS! Authorization Code: $code :: " . $response->getHttpHeader('Location'));
//        }

	$response->send();

	// /**
	//  * 具体基于自己现有的帐号系统验证
	//  * @param $request
	//  * @return bool
	//  */
	// private function checkLogin($request)
	// {
	// 	//todo
	// 	if ($request->request('username') != 'qkl') {
	// 		return $uid = 0; //login faile
	// 	}

	// 	return $uid = 1; //login success
	// }
?>