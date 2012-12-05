<?php

class UsersController extends Controller
{
	protected $model;

	public function __construct() 
	{
		$this->model = new UsersModel();
	}

	public function action($request)
	{
		if ($request->verb == 'POST')
		{
			if (isset($request->url_elements[1]) && $request->url_elements[1] == 'login')
			{
				$data = $this->login($request->parameters);
			}
			else
			{
				$data = $this->createUser($request->parameters);
			}
		}
		else
		{
			errorHandler::sendError(errorHandler::ERRORCODE_403, '403', 'HTTP method not supported.');
		}

		return $data;
	}

	protected function createUser($parameters)
	{
		if (isset($parameters['name'], $parameters['password']) && ! empty($parameters['name']) && ! empty($parameters['password']))
		{
			$name = $parameters['name'];
			$password = $parameters['password'];

			// TODO: nån lätt typ av validering för att se om lösenordet är tillräckligt många tecken och bla bla

			$data = $this->model->insertUserData($name, $password);
		}
		else
		{
			errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Parameters missing or invalid.');
		}

		return $data;
	}

	protected function login($parameters)
	{
		if (isset($parameters['name'], $parameters['password']) && ! empty($parameters['name']) && ! empty($parameters['password']))
		{
			$name = $parameters['name'];
			$password = $parameters['password'];

			$user = $this->model->login($name, $password);

			if ($user)
			{
				Session::setUser($user);
				return true;
			}
			else
			{
				errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Wrong password or name.');
			}
		}
		else
		{
			errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Parameters missing or invalid.');
		}

		return $data;
	}


} 