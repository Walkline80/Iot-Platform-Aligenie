<?php
	function logger($from, $data) {
		// if (empty($from)) {
		// 	$from = "gate";
		// }

		if (is_array($data)) {
			// $data = implode(",", $data);
			$data = json_encode($data);
		}
		
		$data = "[" . now() . "] " . $data . "\r\n\r\n";
		file_put_contents("../log/" . $from . ".log", $data, FILE_APPEND);
	}

	function now() {
		return date("Y-m-d H:i:s");
	}
?>