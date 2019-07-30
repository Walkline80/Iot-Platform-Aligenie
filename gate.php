<?php
	header('Content-Type: application/json');
	header('Cache-Control:no-cache,must-revalidate');
	header('Pragma:no-cache');
	header("Expires: 0");

	use OAuth2\Response;

	$post_str = file_get_contents("php://input");
	file_put_contents("test1.json", $post_str);

	$json_obj = json_decode($post_str, true);

	$namespace = $json_obj['header']['namespace'];
	$name = $json_obj['header']['name'];
	$messageId = $json_obj['header']['messageId'];
	$payLoadVersion = $json_obj['header']['payLoadVersion'];

	$returnObject = array();

	switch($namespace) {
		case 'AliGenie.Iot.Device.Discovery':
			$returnObject = response_to_discovery($messageId, $payLoadVersion);

			break;
	}

	file_put_contents("response.json", json_encode($returnObject));

	echo json_encode($returnObject);







	function response_to_discovery($messageId, $payLoadVersion)
	{
		$header = array(
			"namespace" => "AliGenie.Iot.Device.Discovery",
			"name" =>"DiscoveryDevicesResponse",
			"messageId" => $messageId,
			"payLoadVersion" => $payLoadVersion
		);
		
		$payload = array(
			"devices" => array(
				array(
					"deviceId" => "1234567890",
					"deviceName" => "智能开关",
					"deviceType" => "switch",
					"zone" => "办公室",
					"brand" => "Walkline Hardware",
					"modal" => "wkhw_one_switch",
					"icon" => "https://walkline.wang/logo.png",
					"properties" => array(
						array(
							"name" => "powerstate",
							"value" => "off"
						)
					),
					"actions" => array(
						"TurnOn",
						"TurnOff",
						"Query"
					),
					"extensions" => array(
						"link" => "http://walkline.wang"
					)
				)
			)
		);

		$returnObject = array(
			"header" => $header,
			"payload" => $payload
		);

		return $returnObject;
	}

	function uuid($prefix = '')
	{
		$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		
		return $prefix . $uuid;
	}
?>
