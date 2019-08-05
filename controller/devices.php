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

		return true;
	}
?>