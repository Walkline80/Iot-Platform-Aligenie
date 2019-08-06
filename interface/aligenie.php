<?php
	class Aligenie
	{
		const DEVICE_DISCOVERY = "AliGenie.Iot.Device.Discovery";
		const DEVICE_CONTROL = "AliGenie.Iot.Device.Control";

		const TURN_ON = "TurnOn";
		const TURN_OFF = "TurnOff";

		const DISCOVERY_DEVICES_RESPONSE = "DiscoveryDevicesResponse";
		const TURN_ON_RESPONSE = "TurnOnResponse";
		const TURN_OFF_RESPONSE = "TurnOffResponse";
		
		const ERROR_RESPONSE = "ErrorResponse";

		const DEVICE_IS_NOT_EXIST_CODE = "DEVICE_IS_NOT_EXIST";
		const DEVICE_NOT_SUPPORT_FUNCTION_CODE = "DEVICE_NOT_SUPPORT_FUNCTION";
		
		const DEVICE_IS_NOT_EXIST_MESSAGE = "device is not exist";
		const DEVICE_NOT_SUPPORT_FUNCTION_MESSAGE = "device not support";

		public $namespace = "";
		public $action = "";
		public $messageId = "";
		public $payLoadVersion = 1;
		public $accessToken = "";
		public $deviceId = "";

		function __construct($post_data) {
			$json_obj = json_decode($post_data);
			$header = $json_obj->header;
			$payload = @$json_obj->payload;

			$this->namespace = $header->namespace;
			$this->action = $header->name;
			$this->messageId = $header->messageId;
			$this->payLoadVersion = $header->payLoadVersion;

			$this->accessToken = @$payload->accessToken;
			$this->deviceId = @$payload->deviceId;
		}
	}
?>