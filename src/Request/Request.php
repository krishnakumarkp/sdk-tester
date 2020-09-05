<?php
namespace Src\Request; 
use Bookstore\Client\ObjectSerializer;

class Request 
{
    public $urlElements;
    public $requestMethod;
    public $queryParameters;
    public $formParameters;
    public $uploadedFiles;
    public $bearerToken;
    public $inputFormat;
    public $input;
    public $resource;
    public $resourceId;
    public $data;

    public function __construct() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->urlElements = explode('/', $_SERVER['PATH_INFO']);
        $this->bearerToken = $this->getBearerToken();
        $this->parseIncomingParams();
    }

    private function parseIncomingParams() {
        $parameters = array();
        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }
        $this->queryParameters = $parameters;
        $this->resource = null;
        if(isset($this->urlElements[1])) {
            $this->resource = $this->urlElements[1];
        }
        $this->resourceId = null;
        if(isset($this->urlElements[2])) {
            $this->resourceId = $this->urlElements[2];
        }
        $content_type  = "application/json";
        if(isset($_SERVER['CONTENT_TYPE'])) {
            if(isset($_SERVER['CONTENT_TYPE'])) {
                $content_type = $_SERVER['CONTENT_TYPE'];
            }
            $content_type_parts = explode(";", $content_type);
            $content_type =  $content_type_parts[0];
        }
        switch($content_type) {
            case "application/json":
                $this->inputFormat = "json";
                $this->input = (array) json_decode(file_get_contents('php://input'), TRUE);
                $this->data = json_encode($this->input);
                break;
            case "multipart/form-data":
                $this->formParameters = $_POST;
                if(isset($_FILES["file"])){
                    $this->uploadedFile =  $_FILES["file"]["tmp_name"];
                }
                break;
            case "application/x-www-form-urlencoded":
                 // we could parse other supported formats here
                break;
        }
    } 

    /** 
 * Get Authorization header 
 * */
    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
    * get access token from header
    * */
    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

}