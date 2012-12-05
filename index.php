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

// Allowed main controllers/resources
$allowed_resources = array('debates', 'users');

if (in_array($request->url_elements[0], $allowed_resources))
{
    if (Session::loggedIn() or $request->url_elements[0] == 'users')
    {
        $controller_name = ucfirst($request->url_elements[0]) . 'Controller';
        $controller = new $controller_name();
        $data = $controller->action($request);
        
    }
    else
    {
        errorHandler::sendError(errorHandler::ERRORCODE_401, '401', 'You need to log in.');
    }

    // The view :)
    print_r(json_encode($data));
}
else
{
    errorHandler::sendError(errorHandler::ERRORCODE_404, '404', 'Resource not found.');
}










// try 
// {

//     $controller_name = ucfirst($request->url_elements[0]) . 'Controller';
    

//     // if (class_exists($controller_name)) {
//     //     if ($controller_name != 'UserController' && ! Session::loggedIn())
//     //     {
//     //         throw new Exception("Not logged in");
//     //     }
//     	$controller = new $controller_name();
//     	$result = $controller->action($request);
//     	print_r(json_encode($result));
//     } else {
//         throw new Exception("Couldn't find controller.");
//     }
// } catch (Exception $e) {
//     echo $e->getMessage();
// }