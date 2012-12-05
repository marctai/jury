<?php

class DebatesController extends Controller
{
	public function action($request)
	{
		// Allowed resources for the next step in the URI path
		if ($request->verb == 'GET')
		{
			$allowed_resources = array('open', 'pending', 'finished');
		}
		elseif ($request->verb == 'POST')
		{
			$allowed_resources = array('open');
		}
		elseif ($request->verb == 'PUT')
		{
			$allowed_resources = array('open', 'pending');
		}
		else
		{
			terrorHandler::sendError(errorHandler::ERRORCODE_403 , '403', 'HTTP method not supported.');
		}

		$data = $this->routeActions($request, $request->verb, $allowed_resources);
		return $data;
	}
}