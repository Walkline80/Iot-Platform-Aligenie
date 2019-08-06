<?php
	header('Content-Type: application/json');   
	header('Cache-Control:no-cache,must-revalidate');
	header('Pragma:no-cache');
	header("Expires: 0");

	require_once('../inc/function.php');
	require_once('../controller/server.php');
	require_once('../controller/devices.php');
	require_once('aligenie.php');

	$post_data = file_get_contents("php://input");

	logger("gate", $post_data);

	$aligenie = new Aligenie($post_data);
	$returnObject->header->messageId = $aligenie->messageId;
	$returnObject->header->payLoadVersion = $aligenie->payLoadVersion;

	switch($aligenie->namespace) {
		case Aligenie::DEVICE_DISCOVERY:
			$uuid = get_user_id_for_aligenie($aligenie->accessToken);

			$returnObject->header->namespace = $aligenie->namespace;
			$returnObject->header->name = Aligenie::DISCOVERY_DEVICES_RESPONSE;

			$devices = get_device_lists($uuid);

			if (is_array($devices)) {
				$returnObject->payload->devices = $devices;
			} else {
				$returnObject->payload = null;
			}

			break;
		case Aligenie::DEVICE_CONTROL:
			$uuid = get_user_id_for_aligenie($aligenie->accessToken);
			
			$returnObject->header->namespace = $aligenie->namespace;
			$returnObject->payload->deviceId = $aligenie->deviceId;

			switch ($aligenie->action) {
				case Aligenie::TURN_ON:
					if (set_device_status($uuid, $aligenie->deviceId, 1)) {
						$returnObject->header->name = Aligenie::TURN_ON_RESPONSE;
					} else {
						$returnObject->header->name = Aligenie::ERROR_RESPONSE;
						$returnObject->payload->errorCode = Aligenie::DEVICE_IS_NOT_EXIST_CODE;
						$returnObject->payload->message = Aligenie::DEVICE_IS_NOT_EXIST_MESSAGE;
					}

					break;
				case Aligenie::TURN_OFF:
					if (set_device_status($uuid, $aligenie->deviceId, 0)) {
						$returnObject->header->name = Aligenie::TURN_OFF_RESPONSE;
					} else {
						$returnObject->header->name = Aligenie::ERROR_RESPONSE;
						$returnObject->payload->errorCode = Aligenie::DEVICE_IS_NOT_EXIST_CODE;
						$returnObject->payload->message = Aligenie::DEVICE_IS_NOT_EXIST_MESSAGE;
					}

					break;
				default:
					$returnObject->header->name = Aligenie::ERROR_RESPONSE;
					$returnObject->payload->errorCode = Aligenie::DEVICE_NOT_SUPPORT_FUNCTION_CODE;
					$returnObject->payload->message = Aligenie::DEVICE_NOT_SUPPORT_FUNCTION_MESSAGE;
			}
	}

	logger("gate", json_encode($returnObject));

	echo json_encode($returnObject);

/**
 * {
 *	"header": {
 *		"messageId": "7d6387b9-b9f5-4c47-af86-33b364ee26bf",
 *		"name": "DiscoveryDevices",
 *		"namespace": "AliGenie.Iot.Device.Discovery",
 *		"payLoadVersion": 1
 *	},
 *	"payload": {
 *		"accessToken": "14d933b6a08b4af4c9349ff020f28607babb1153"
 *	}
 *}
 */

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
?>
