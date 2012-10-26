<?php

class Request
{
	public $url_elements;
	public $verb;
	public $parameters;

	public function __construct()
	{
		$this->verb = $_SERVER['REQUEST_METHOD'];
		if (isset($_SERVER['PATH_INFO'])) {
			$this->url_elements = explode('/', $_SERVER['PATH_INFO']);
			array_shift($this->url_elements);
		}
		if ($this->url_elements[0] == '') {
			// Default controller
			$this->url_elements[0] = 'users';
		}
		$this->parseIncomingParams();
		// Initialise JSON as default format
		$this->format = 'json';
		if (isset($this->parameters['format'])) {
			$this->format = $this->parameters['format'];
		}
		return true;
	}

	public function parseIncomingParams()
	{
		$parameters = array();
		// First pull the GET vars
		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $parameters);
		}

		//
		$body = file_get_contents("php://input");
		$content_type = false;
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$content_type = $_SERVER['CONTENT_TYPE'];
		}

		switch($content_type) {
			case "application/json":
				$body_params = json_decode($body);
				if ($body_params) {
					foreach ($body_params as $param_name => $param_value) {
						$parameters[$param_name] = $param_value;
					}
				}
				$this->format = 'json';
				break;
			default:
				// Other supported formats here
				break;
		}
		$this->parameters = $parameters;
	}
}