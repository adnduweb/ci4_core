<?php

namespace Adnduweb\Ci4Core\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;

class Tests extends ResourceController
{
    protected $format    = 'json';

    public function __construct()
    {
        //$this->options();
    }


    public function index()
    {
        try {
            $data = [];
            $response['data'] = $data;
            $response['success'] = true;
            $response['message'] = "Successful load";
            return $this->respond($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $this->respond($response);
        }
    }

    // ...

    // public function options()
    // {
    //     return service('response')->setHeader('Access-Control-Allow-Origin', 'spreadci4.lan') //for allow any domain, insecure
    //         ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization') //for allow any headers, insecure
    //         ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
    //         ->setStatusCode(204); //status code
    // }
}
