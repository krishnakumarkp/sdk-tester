<?php
namespace Src\Controller; 
use Src\Request\Request;

class ControllerFactory {

    public static function Create(Request $request): ?ResourceController  
    {
        $httpClient = new \GuzzleHttp\Client();
        if(isset($request->resource)) {
            $request->resource = str_replace("-", "",$request->resource);
            $className = sprintf("Src\Controller\%sController", ucfirst($request->resource));
            if(class_exists($className)) {
                return  new $className($request,$httpClient);
            }
        }
        return new DefaultController($request,$httpClient);
    }
}
