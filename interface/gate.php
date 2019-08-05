<?php
	header('Content-Type: application/json');   
	header('Cache-Control:no-cache,must-revalidate');
	header('Pragma:no-cache');
	header("Expires: 0");

	use OAuth2\Response;

	require_once('../inc/function.php');
	require_once('../controller/server.php');
	require_once('../controller/devices.php');

	$post_str = file_get_contents("php://input");

	logger("gate", $post_str);

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
		case 'AliGenie.Iot.Device.Control':
			$accessToken = $json_obj['payload']['accessToken'];
			$deviceId = $json_obj['payload']['deviceId'];
			$uuid = get_user_id_for_aligenie($accessToken);
			
			switch ($name) {
				case 'TurnOn':
					if (set_device_status($uuid, $deviceId, 1)) {
						$returnObject = response_to_turnon($messageId, $payLoadVersion, $deviceId);
					} else {
						$returnObject = response_to_turnon($messageId, $payLoadVersion, $deviceId);
					}

					break;
				case 'TurnOff':
					if (set_device_status($uuid, $deviceId, 0)) {
						$returnObject = response_to_turnoff($messageId, $payLoadVersion, $deviceId);
					} else {
						$returnObject = response_to_turnoff($messageId, $payLoadVersion, $deviceId);
					}

					break;
			}
	}

	logger("gate", json_encode($returnObject));

	echo json_encode($returnObject);

/**
 * {
 *	"header": {
 *		"messageId": "76e37c26-c4e0-4e9c-ab48-e9379f41a1c5",
 *		"name": "TurnOn",
 *		"namespace": "AliGenie.Iot.Device.Control",
 *		"payLoadVersion": 1
 *	},
 *	"payload": {
 *		"accessToken": "7cb4d262082cbaefd35469dd6d11586096e4575d",
 *		"attribute": "powerstate",
 *		"deviceId": "1234567890",
 *		"deviceType": "switch",
 *		"extensions": {
 *			"link": "http://walkline.wang"
 *		},
 *		"value": "on"
 *	}
 *}
 */

	function response_to_turnon($messageId, $payLoadVersion, $deviceId)
	{
		$header = array (
			"namespace" => "AliGenie.Iot.Device.Control",
			"name" => "TurnOnResponse",
    		"messageId" => $messageId,
    		"payLoadVersion" => $payLoadVersion
		);

		$payload = array (
			"deviceId" => $deviceId
		);

		$returnObject = array(
			"header" => $header,
			"payload" => $payload
		);

		return $returnObject;
	}

	function response_to_turnon_failed($messageId, $payLoadVersion, $deviceId)
	{
		$header = array (
			"namespace" => "AliGenie.Iot.Device.Control",
			"name" => "TurnOnResponse",
    		"messageId" => $messageId,
    		"payLoadVersion" => $payLoadVersion
		);

		$payload = array (
			"deviceId" => $deviceId
		);

		$returnObject = array(
			"header" => $header,
			"payload" => $payload
		);

		return $returnObject;
	}

	function response_to_turnoff($messageId, $payLoadVersion, $deviceId)
	{
		$header = array (
			"namespace" => "AliGenie.Iot.Device.Control",
			"name" => "TurnOffResponse",
    		"messageId" => $messageId,
    		"payLoadVersion" => $payLoadVersion
		);

		$payload = array (
			"deviceId" => $deviceId
		);

		$returnObject = array(
			"header" => $header,
			"payload" => $payload
		);

		return $returnObject;
	}

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
					"deviceId" => "73aa1223-ac41-11e9-b2b6-7085c2ae4575",
					"deviceName" => "开关",
					"deviceType" => "switch",
					"zone" => "办公室",
					"brand" => "Walkline Hardware",
					"modal" => "wkhw_one_switch",
					"icon" => "https://walkline.wang/logo.png",
					"properties" => array(
						array(
							"name" => "powerstate",
							"value" => "on"
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
