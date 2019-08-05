<?php
	require_once('connect2db.php');

	function get_login_h5() {
		$content = file_get_contents('template/index.html', true);

		return $content;
	}

	function get_user_id_for_platform($param) {
		$result = post_url_data("http://walkline.wang/iot/inc/api/platform/v1/get_user_id", $param);

		$jsonObject = json_decode($result, true);

		if ($jsonObject['error_code']) {
			exit("<script>alert('" . $jsonObject['error_msg'] . "');location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>");
		} else {
			return $jsonObject['uuid'];
		}
	}

	function get_user_id_for_aligenie($accessToken) {
		global $mysqli;

		$query = "SELECT
			user_id
		FROM
			oauth_access_tokens
		WHERE
			access_token = ?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $accessToken);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($uuid);
		$stmt->fetch();

		return $uuid;
	}

	function post_url_data($url, $params) {
        ini_set("max_execution_time", 100);
		$curl = curl_init();
		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $params
		);

		curl_setopt_array($curl, $options);
        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
?>