<?php

class OpenController
{
	protected $resources;
	protected $parameters;
	protected $model;

	public function __construct($resources, $parameters)
	{
		$this->model = new OpenModel();
		$this->resources = $resources;
		$this->parameters = $parameters;
	}

	public function GET()
	{
		// Will first return debates waiting for an opponent, if no debates exists it will return a new random subject (if subjects exists)
		return $this->model->getDebate();
	}

	public function POST()
	{
		if (isset($this->parameters['argument'], $this->parameters['stance'], $this->parameters['subject_id']) 
			&& !empty($this->parameters['argument']) 
			&& !empty($this->parameters['stance'])
			&& is_numeric($this->parameters['subject_id']))
		{
			$argument = $this->parameters['argument'];
			$stance = $this->parameters['stance'];
			$subject_id = $this->parameters['subject_id'];
			
			return $this->model->insertDebate($subject_id, $stance, $argument);
		}
		else
		{
			errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Missing or invalid parameters.');
		}
	}

	public function PUT()
	{
		// Check if next resource is set and is a number
		if (isset($this->resources[0]) && ctype_digit($this->resources[0])) 
		{
			$id = $this->resources[0];
			if (isset($this->parameters['argument'], $this->parameters['stance'], $this->parameters['subject_id']) 
				&& !empty($this->parameters['argument']) 
				&& !empty($this->parameters['stance']) 
				&& is_numeric($this->parameters['subject_id'])) 
			{
				$argument = $this->parameters['argument'];
				$stance = $this->parameters['stance'];
				$subject_id = $this->parameters['subject_id'];
				
				return $this->model->insertDebate($subject_id, $stance, $argument, $id);
			} 
			else 
			{
				errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Missing or invalid parameters.');	
			}
		}
		else 
		{
			errorHandler::sendError(errorHandler::ERRORCODE_400, '400', 'Missing or invalid ID.');
		}
	}
}