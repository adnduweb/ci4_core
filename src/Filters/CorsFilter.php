<?php

namespace Adnduweb\Ci4Core\Filters;

use CodeIgniter\Config\Config;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Adnduweb\Ci4Core\Libraries\ServiceCors;

class CorsFilter implements FilterInterface
{
    /**
     * @var \Fluent\Cors\ServiceCors $cors
     */
    protected $cors;

    /**
     * Constructor.
     *
     * @param array $options
     * @return void
     */
    public function __construct()
    {
        $this->cors = new ServiceCors(static::defaultOptions());

        //print_r($this->cors);exit;
    }

    /**
     * @inheritdoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        var_dump($request->getHeader('API_KEY')); exit;

        if( $request->hasHeader('API_KEY') != service('settings')->setting_key_api){
            die(lang('Core.not_key_api_authorized'));
        }

        
    //     if ($this->cors->isPreflightRequest($request)) { 
    // //        echo 'fdgsdfgsdg'; exit;
    //         $response = $this->cors->handlePreflightRequest($request);
    //         $this->cors->varyHeader($response, 'Access-Control-Request-Method');

    //         return $response;
    //     }

            // $response = $this->cors->handlePreflightRequest($request);
            // $this->cors->varyHeader($response, 'Access-Control-Request-Method');

            // return $response;

    }

    /**
     * @inheritdoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // //var_dump($this->cors->isCorsRequest($request)); exit;
        // if ($this->cors->isCorsRequest($request)) {
        //     $this->cors->handleRequest($request, $response);
        // }

       // print_r($request); exit;
       
        
        // return $response;
        return $response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
        ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization') //for allow any headers, insecure
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
        ->setStatusCode(200); //status code

       //$this->cors->handleRequest($request, $response);
        
    }

    /**
     * Default config options.
     *
     * @return array
     */
    protected static function defaultOptions()
    {
        $config = Config::get('Cors');

        return [
            'allowedHeaders'      => $config->allowedHeaders,
            'allowedMethods'      => $config->allowedMethods,
            'allowedOrigins'      => $config->allowedOrigins,
            'exposedHeaders'      => $config->exposedHeaders,
            'maxAge'              => $config->maxAge,
            'supportsCredentials' => $config->supportsCredentials,
        ];
    }
}