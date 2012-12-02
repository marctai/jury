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
		// TODO: filtrering och validering
		$id = $this->resources[0];
		$stance = $this->parameters['vote'];
		return $this->model->vote($id, $stance);
	}
}