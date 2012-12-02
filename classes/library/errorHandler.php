<?php

class errorHandler
{
	const ERRORCODE_404 = '404 Not Found';
	const ERRORCODE_500 = '500 Internal Server Error';
	const ERRORCODE_304 = '304 Not Modified';
	const ERRORCODE_400 = '400 Bad Request';
	const ERRORCODE_401 = '401 Unauthorized';
	const ERRORCODE_403 = '403 Forbidden';

	public static function sendError($error_const, $code, $message)
	{
		$header_string = 'HTTP/1.1 ' . $error_const;
		$error_message = array('code' => $code, 'message' => $message);
		header($header_string);
		print_r(json_encode($error_message));
		die();
	}
}