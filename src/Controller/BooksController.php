<?php
namespace Src\Controller;
use Src\Controller\ResourceController;
use Bookstore\Client\ObjectSerializer;

class BooksController extends ResourceController {

    public function __construct($request, $httpClient)
    {
        parent::__construct($request);
        $this->apiInstance = new \Bookstore\Client\Api\BooksApi($httpClient, $this->config);
        $this->createRequestClassName = '\Bookstore\Client\Model\Newbook';
        $this->createMethodName = 'addBookWithHttpInfo';
        $this->updateRequestClassName = '\Bookstore\Client\Model\Newbook';
        $this->updateMethodName = "updateBookWithHttpInfo";
        $this->getByIdMethodName = "findBookWithHttpInfo";
        $this->deleteMethodName = "deleteBookWithHttpInfo";
    }

    protected function getAll() {
        try {
            list($result,$code,$header) = $this->apiInstance->listBookWithHttpInfo();
            $response['header'] = $header;
            $response['status_code'] = $code;
            $response['body'] = json_encode(ObjectSerializer::sanitizeForSerialization($result));
        } 
        catch (\Bookstore\Client\ApiException $e) {
           $response['header'] = $e->getResponseHeaders();
           $response['status_code'] = $e->getCode();
           $response['body'] = $e->getResponseBody();
        }
        return  $response;
    }

}