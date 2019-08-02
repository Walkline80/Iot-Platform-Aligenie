<?php
	require_once('connect2db.php');

	function get_login_h5() {
		$content = file_get_contents('template/index.html', true);

		return $content;
	}
?>