<?php

class DebatesController extends MyController
{
	protected $model;

	public function __construct() 
	{
		$this->model = new DebatesModel();
		return true;
	}

	protected function routeActions($request, $method, $allowed_dirs)
	{
		$data = '';

		if (isset($request->url_elements[1]) && !empty($request->url_elements[1])) {
			$dir = $request->url_elements[1];
			
			if (in_array($dir, $allowed_dirs)) {
				$dirs = array_slice($request->url_elements, 2);
				$action = $method . ucfirst($dir);
				$data = $this->$action($dirs, $request->parameters);
			} else {
				throw new Exception("Couldn't find " . $dir);
			}
		}
		return $data;
	}


	public function getAction($request)
	{
		$allowed_dirs = array('open', 'pending', 'finished');	
		$data = $this->routeActions($request, 'get', $allowed_dirs);
		return $data;
	}

	public function postAction($request)
	{
		$allowed_dirs = array('open');
		$data = $this->routeActions($request, 'post', $allowed_dirs);
		return $data;
	}

	public function putAction($request)
	{
		$allowed_dirs = array('open');
		$data = $this->routeActions($request, 'put', $allowed_dirs);
		return $data;
	}

	protected function getPending($dirs, $params)
	{
		// Get debates waiting for votes
		return $this->model->getPending();
	}

	protected function putPending($id)
	{
		// ...
	}

	protected function getOpen($dirs, $params)
	{
		$allowed_dirs = array('random');

		if (!empty($dirs) && !$dirs[0] == '') {

			if (in_array($dirs[0], $allowed_dirs)) {
				// open/random stuff!
				return 'open/' . $dirs[0];
			} else {
				throw new Exception("Couldn't find " . $dirs[0]);
			}
		}
		return $this->model->getOpen();
	}

	protected function putOpen($dirs, $params)
	{
		// Check if dir is a number
		if (isset($dirs[0]) && ctype_digit($dirs[0])) {

			if (isset($params['argument']) && isset($params['stance']) && isset($params['subject_id'])) {
				// TODO: Some kind of validation of params
				// print_r($params);
				return $this->model->putOpen($params['subject_id'], $params['stance'], $params['argument'], $dirs[0]);
			} else {
				throw new Exception("Missing parameters");
				
			}
		} else {
			throw new Exception("Missing ID");
		}
	}

	protected function postOpen($dirs, $params)
	{
		// TODO: Validate and filter params
		$stance = $params['stance'];
		$argument = $params['argument'];
		$subject_id = $params['subject_id'];

		return $this->model->postOpen($subject_id, $stance, $argument);
	}

	protected function getFinished($params, $method)
	{
		return 'finished';
	}
}