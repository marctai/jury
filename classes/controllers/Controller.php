<?php

class Controller
{
	protected function routeActions($request, $method, $allowed_resources)
	{
		$data = '';

		// Check if a next resource has been set
		if (isset($request->url_elements[1]) && !empty($request->url_elements[1])) 
		{
			$resource = $request->url_elements[1];
			
			// If resource is allowed, init corresponding controller
			if (in_array($resource, $allowed_resources)) 
			{
				$resources = array_slice($request->url_elements, 2);
				$controller = ucfirst($resource).'Controller';
				$resource = new $controller($resources, $request->parameters);
				$data = $resource->$method();
			} 
			else 
			{
				errorHandler::sendError(errorHandler::ERRORCODE_404, '404', 'Resource not found');
			}
		}
		return $data;
	}
}