<?php
require_once('../vendor/autoload.php');
use Src\Request\Request;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,HEAD,POST,PUT,DELETE,OPTIONS,PATCH");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Connection: Keep-alive");

const DR_API_ENDPOINT = "http://192.168.100.10:8080/api/v1";

$request = new Request();

//authenticate the request 
// if (!isset($request->bearerToken)) {
//     header('HTTP/1.0 401 Unauthorized');
//     $status = array('error' => 1, 'message' => 'Access denied 401!');
//     echo json_encode($status);
//     exit;
// }

//configure sdk
$controller =  Src\Controller\ControllerFactory::Create($request);

$controller->processRequest();
