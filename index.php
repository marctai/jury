<?php

session_start();

spl_autoload_register(function($class_name) {
    $file = 'classes/controllers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once($file);
        return true;
    }
    $file = 'classes/models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once($file);
        return true;
    }
    $file = 'classes/library/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once($file);
        return true;
    }
    return false;
});


$request = new Request();
// echo '<pre>';
// print_r($_SERVER);
// echo '<br />';
// print_r($request->url_elements);
// echo '</pre><br />';
// $e = class_exists('Controller');
// die($e);

// Route the request to the right place
try {
    $controller_name = ucfirst($request->url_elements[0]) . 'Controller';
    if (class_exists($controller_name)) {
    	$controller = new $controller_name();
    	$action_name = strtolower($request->verb) . 'Action';
    	$result = $controller->$action_name($request);
    	print_r(json_encode($result));
    } else {
        throw new Exception("Couldn't find controller.");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}