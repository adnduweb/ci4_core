<?php

namespace Adnduweb\Ci4Core\Controllers\API;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;
use \Adnduweb\Ci4Admin\Libraries\Theme;


abstract class BaseApiController extends \CodeIgniter\Controller
{

    /**
     * @var helpers
     */
    protected $helpers = ['detect', 'url', 'form', 'lang'];

    /**
     * Refactored class-wide data array variable
     * 
     * @var array
     */
    protected $viewData = [];

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var Pager
     */
    protected $pager;

    public $locale;

    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * @var \CodeIgniter\Services\encrypter
     */
    protected $encrypter;

    /**
     * @var \Config\Services::validation();
     */
    protected $validation;

    /**
     * @array array ;
     */
    protected $rules;

    /**
     * Silent
     */
    public $silent = true;



    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.:

        $this->session   = service('session');
        $this->encrypter = service('encrypter');
        $this->uuid      = service('uuid');

        //Check language
        $this->langue = service('LanguageOverride');
        setlocale(LC_TIME, service('request')->getLocale() . '_' .  service('request')->getLocale());

        //--------------------------------------------------------------------
        // Check for flashdata
        //--------------------------------------------------------------------
        $this->viewData['confirm'] = $this->session->getFlashdata('confirm');
        $this->viewData['errors']  = $this->session->getFlashdata('errors');
        $this->viewData['html']    = detectBrowser(true);

        $this->settings   = service('settings');
        $this->validation = service('validation');
        $this->db         = Database::connect();
       // $this->tableModel = (!is_null($this->tableModel)) ? new $this->tableModel : null;

        // Display theme information
        $this->viewData['theme_front'] = $this->settings->setting_theme_admin;
        $this->viewData['metatitle']   = $this->controller;

       // $this->options();
        
    }

    protected function _render(string $view, array $data = [])
    {
        return view($view, $data);
    }


    // try to cache a setting and pass it back
    protected function cache($key, $content)
    {
        if ($content === null) {
            return cache()->delete($key);
        }

        if ($duration = env('cache.cacheDuration')) {
            cache()->save($key, $content, $duration);
        }
        return $content;
    }

    protected function redirect(string $url)
    {
        return service('response')->redirect($url);
    }

    protected function goHome()
    {
        return $this->redirect(route_to('home'));
    }

    // public function options()
    // {
    //     return service('response')->setHeader('Access-Control-Allow-Origin', 'spreadci4.lan') //for allow any domain, insecure
    //         ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization') //for allow any headers, insecure
    //         ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
    //         ->setStatusCode(200); //status code
    // }

  
}
