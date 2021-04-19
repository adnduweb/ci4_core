<?php namespace Adnduweb\Ci4Core\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class AuthApi implements FilterInterface {

    public function before(RequestInterface $request, $params = null)  {
//https://forum.codeigniter.com/thread-76046.html?highlight=filters
    $token = $request->getServer('HTTP_AUTHORIZATION')

    if($token == null){
      // How can I send the response that showing unauthorized 401?
      // 
    }

  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {

    $response = service('response');
    $response->setStatusCode(401);

    return $response; 

  }

}