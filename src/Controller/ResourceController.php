<?php
namespace Src\Controller; 
use Bookstore\Client\ObjectSerializer;


abstract class ResourceController {

    protected  $config;
    protected  $apiInstance;
    protected  $createRequestClassName;
    protected  $createMethodName;
    protected  $updateRequestClassName;
    protected  $updateMethodName;
    protected  $getByIdMethodName;
    protected  $deleteMethodName;
    protected  $request;

    public function __construct($request)
    {
       
        $this->request = $request;
    }

    public function processRequest()
    {        
        switch ($this->request->requestMethod) {
            case 'GET':
                if ($this->request->resourceId) {
                    $response = $this->getById();
                } else {
                    $response = $this->getAll();
                };
                break;
            case 'POST':
                $response = $this->create();
                break;
            case 'PUT':
                if ($this->request->resourceId) {
                    $response = $this->update();
                } 
                break;
            case 'DELETE':
                if ($this->request->resourceId) {
                    $response = $this->delete();
                } else {
                    $response = $this->notFoundResponse();
                };
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        http_response_code($response['status_code']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    abstract protected function getAll();
    
    protected function getById() {
        $resourceId = $this->request->resourceId;
        $methodName = $this->getByIdMethodName;
        try {
            if(method_exists($this->apiInstance, $methodName)) {
                list($result,$code, $header) = $this->apiInstance->{$methodName}($resourceId);
                $response['header'] = $header;
                $response['status_code'] = $code;
                $response['body'] = (string)$result;
            } else {
                $response = $this->notFoundResponse();
            }
        } 
        catch (\Bookstore\Client\ApiException $e) {
            $response['header'] = $e->getResponseHeaders();
            $response['status_code'] = $e->getCode();
            $response['body'] = $e->getResponseBody();
        }
        return  $response;
    }
    protected function create() {       
        $createRequest = ObjectSerializer::deserialize( $this->request->data, $this->createRequestClassName, []);
        $createMethodName = $this->createMethodName;
         
         try {
            if(method_exists($this->apiInstance, $createMethodName)) {
                list($result,$code, $header) = $this->apiInstance->{$createMethodName}($createRequest);
                $response['header'] = $header;
                $response['status_code'] = $code;
                $response['body'] = (string)$result;
            } else {
                $response = $this->notFoundResponse();
            }
        } catch (\Bookstore\Client\ApiException $e) {
            $response['header'] = $e->getResponseHeaders();
            $response['status_code'] = $e->getCode();
            $response['body'] = $e->getResponseBody();
        }
        return  $response; 
    }
    protected function update() {
        $resourceId = $this->request->resourceId;
        $updateRequest = ObjectSerializer::deserialize(json_decode($this->request->data), $this->updateRequestClassName, []);
        $updateMethodName = $this->updateMethodName;

        try {
            if(method_exists($this->apiInstance, $updateMethodName)) {
                list($result,$code, $header) = $this->apiInstance->{$updateMethodName}($resourceId, $updateRequest);
                $response['header'] = $header;
                $response['status_code'] = $code;
                $response['body'] = (string)$result;
            } else {
                $response = $this->notFoundResponse();
            }
        } catch (\Bookstore\Client\ApiException $e) {
            $response['header'] = $e->getResponseHeaders();
            $response['status_code'] = $e->getCode();
            $response['body'] = $e->getResponseBody();
        }
        return  $response; 
    }
    protected function delete() {
        $resourceId = $this->request->resourceId;
        $deleteMethodName = $this->deleteMethodName;
        try {
            if($deleteMethodName && method_exists($this->apiInstance, $deleteMethodName)) {
                list($result,$code, $header) = $this->apiInstance->{$deleteMethodName}($resourceId);
                $response['header'] = $header;
                $response['status_code'] = $code;
                $response['body'] = (string)$result;
            } else {
                $response = $this->notFoundResponse();
            }
        } catch (\Bookstore\Client\ApiException $e) {
            $response['header'] = $e->getResponseHeaders();
            $response['status_code'] = $e->getCode();
            $response['body'] = $e->getResponseBody();
        }
        return  $response; 
    }

    protected function unprocessableEntityResponse()
    {
        $response['status_code'] = 422;
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    protected function notFoundResponse()
    {
        $response['status_code'] = 404;
        $response['body'] = null;
        return $response;
    }

}