<?php

class PendingController
{
	protected $resources;
	protected $parameters;
	protected $model;

	public function __construct($resources, $parameters)
	{
		$this->model = new PendingModel();
		$this->resources = $resources;
		$this->parameters = $parameters;
	}

	public function GET()
	{
		return $this->model->getPendingDebates();
	}

	public function PUT()
	{
		if (isset($this->resources[0]) && ctype_digit($this->resources[0]))
		{
			$id = $this->resources[0];
			
			if (isset($this->parameters['vote']) && ($this->parameters['vote'] == 'for' || $this->parameters['vote'] == 'against'))
			{
				$vote = $this->parameters['vote'];
				return $this->model->vote($id, $vote);
			}
			else
			{
				errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Missing vote parameter or vote parameter set to invalid value.');
			}
		}
		else
		{
			errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Missing or invalid ID.');
		}	
	}
}