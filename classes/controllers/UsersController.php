<?php

class UsersController extends MyController
{
	public function getAction($request) 
	{
		if (isset($request->url_elements[1])) {
			$user_id = (int) $request->url_elements[1];
			if (isset($request->url_elements[2])) {
				switch ($request->url_elements[2]) {
					case 'friends':
						$data['message'] = 'user ' . $user_id . 'has many friends';
						break;
					default:
						// Not a supported action
						break;
				}
			} else {
				$data['message'] = 'here is the info for user ' . $user_id;
			}
		} else {
			$data['message'] = 'you want a list of users';
		}
		return $data;
	}

	public function postAction($request)
	{
		$data = $request->parameters;
		$data['message'] = 'this data was submitted';
	}
} 