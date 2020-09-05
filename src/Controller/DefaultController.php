<?php
namespace Src\Controller;
use Src\Controller\ResourceController;
class DefaultController extends ResourceController {

    public function __construct($request, $httpClient)
    {
        parent::__construct($request);
        $this->apiInstance = "";
    }

    public function processRequest()
    {
        $response = $this->notFoundResponse();
        http_response_code($response['status_code']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    protected function getAll() {
        $response = $this->notFoundResponse();
        return $response;
    }

}