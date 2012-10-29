<?php

class DebatesController extends MyController
{
	public function getAction($request)
	{
		$allowed = array('open', 'pending', 'finished');
		$data = '';

		if (isset($request->url_elements[1]) && !empty($request->url_elements[1])) {
				$dir = $request->url_elements[1];
			
			if (in_array($dir, $allowed)) {
				$params = array_slice($request->url_elements, 2);
				$method = 'get' . ucfirst($dir);
				$data = $this->$method($params);
			} else {
				throw new Exception("Couldn't find " . $dir);
			}
		}
		return $data;
	}

	public function postAction($request)
	{
		// ...
	}

	protected function getOpen($params)
	{
		$allowed = array('random');

		if (!empty($params) && !$params[0] == '') {
			
			if (in_array($params[0], $allowed)) {
				// open/random stuff!
				return 'open/' . $params[0];
			} else {
				throw new Exception("Couldn't find " . $params[0]);
			}
		}
		return 'open';
	}

	protected function getPending($params)
	{
		return 'pending';
	}

	protected function getFinished($params, $method)
	{
		return 'finished';
	}
}