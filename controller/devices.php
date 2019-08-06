<?php
	require_once('connect2db.php');

	function set_device_status($uuid, $device_id, $status) {
		global $mysqli;

		if (!isset($uuid) || !isset($device_id) || !isset($status)) {
			return false;
		}

		$query = 
			"UPDATE iot_devices AS devices
			INNER JOIN iot_users AS users ON (users.uuid = devices.uuid)
			SET devices.wanted = ?
			WHERE
				users.uuid = ?
			AND devices.`key` = ?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("iss", $status, $uuid, $device_id);
		$stmt->execute();

		$stmt->close();
		$mysqli->close();

		return true;
	}

	function get_device_lists($uuid) {
		global $mysqli;

		if (!isset($uuid)) {
			return false;
		}

		$query =
			"SELECT
				`key`,
				(
					CASE
					WHEN `status` = 1 THEN
						'on'
					ELSE
						'off'
					END
				) AS `status`,
				aligenie_name,
				aligenie_type,
				aligenie_zone,
				aligenie_brand,
				aligenie_modal,
				aligenie_icon
			FROM
				iot_devices
			WHERE
				uuid = ?
			AND aligenie_enabled = 1";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $uuid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($device_id, $status, $name, $type, $zone, $brand, $modal, $icon);
		
		$devices = array();

		while ($stmt->fetch()) {
			$list = array(
				"deviceId" => $device_id,
				"deviceName" => $name,
				"deviceType" => $type,
				"zone" => $zone,
				"brand" => $brand,
				"modal" => $modal,
				"icon" => $icon,
				"properties" => array(
					array(
						"name" => "powerstate",
						"value" => $status
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
			);

			$devices[] = $list;
		}

		$stmt->close();
		$mysqli->close();

		return $devices;
	}


	// array(
	// 	"devices" => array(
	// 		array(
	// 			"deviceId" => "73aa1223-ac41-11e9-b2b6-7085c2ae4575",
	// 			"deviceName" => "开关",
	// 			"deviceType" => "switch",
	// 			"zone" => "办公室",
	// 			"brand" => "Walkline Hardware",
	// 			"modal" => "wkhw_one_switch",
	// 			"icon" => "https://walkline.wang/logo.png",
	// 			"properties" => array(
	// 				array(
	// 					"name" => "powerstate",
	// 					"value" => "on"
	// 				)
	// 			),
	// 			"actions" => array(
	// 				"TurnOn",
	// 				"TurnOff",
	// 				"Query"
	// 			),
	// 			"extensions" => array(
	// 				"link" => "http://walkline.wang"
	// 			)
	// 		)
	// 	)
	// );
?>
