<?php

namespace Adnduweb\Ci4Core\Core;

use CodeIgniter\Model;
use Adnduweb\Ci4Core\Exceptions\CoreException;

class Language extends BaseLanguage
{
    /**
     * 
     */
    protected $listFormat = [];

     /**
     * 
     */
    protected $languageId;    

    /**
     * @var Model
     */
    protected $languageModel;

    /**
     * 
     */
    protected $langueCurrent = '';

    /**
     * 
     */
    protected $AllLangue = '';

     /**
     * 
     */
    protected static $langueSupported = [];


    /**
     * 
     */
    public function __construct(){

        $this->langueCurrent = service('request')->getLocale();

    }

    public static function listFormat() {
    }

    public static function getFormat($format = null) {
    }

    public static function switch($lang = null) {
        
        if (!$lang) die('Provide language code from language list');

        // Create session if not created
        //if (session_status() == PHP_SESSION_NONE) session_start();

        $list = self::list();
        foreach ($list as $key => $value) {
            if ( strtolower($value['code']) == strtolower($lang) ) {
                session()->set('lang', $lang);
                return session()->get('lang');
            }
        }
        die('There is no such language inside language list. Check all languages - \'LanguageSwitcher::list()\'');
    }

    public function getIdLocale()
    {
       // echo $this->langueCurrent; 
        $languageRes = $this->languageModel->where(['iso_code' => $this->langueCurrent, 'active' => 1])->first();

        if(!$languageRes){
            throw CoreException::notActived($this->langueCurrent);
        }

        return $this->languageId = $languageRes->id;
    }

    public function supportedLocales() {
        $allLanguage = $this->languageModel->where('active', 1)->findAll();

        if(!$allLanguage){
            throw CoreException::LanguageNotExist();
        }

        foreach($allLanguage as $lang){
            self::$langueSupported[] = $lang->iso_code;
        }

       return  self::$langueSupported;
    }

    // public function getFormat()
    // {
    //     $format['fr'] = 'd-m-Y H:i:s';
    //     $format['en'] = 'Y-m-d H:i:s';
    //     $langueCurrent = service('request')->getLocale();
    //     return $format[$langueCurrent];
    // }
 
    public function getArrayLanguesSupported($iso = true)
    {
        //$setting_supportedLocales = json_decode(service('settings')->setting_supportedLocales);
        $setting_supportedLocales = ['1|fr', '2|en'];
        $tabs = [];
        $i =0;
         foreach ($setting_supportedLocales as $k => $v) {
            $langExplode = explode('|', $v);
            if($iso == true){
                $tabs[$langExplode[1]] = $langExplode[0];
            }else{
                $tabs[$langExplode[0]] = $langExplode[1];
            }
            $i++;
        }
        return $tabs;
    }


    public function redirect()
    {   $session = session();
        $locale = $this->request->getLocale();
        $session->remove('lang');
        $session->set('lang',$locale);
        $url = base_url();
        return redirect()->to($url);     
    }

    //--------------------------------------------------------------------
    // Model Setters
    //--------------------------------------------------------------------

    /**
     * Sets the model 
     *
     * @param \Adnduweb\Ci4Core\Model $model
     *
     * @return $this
     */
    public function setLanguageModel(Model $model)
    {
        $this->languageModel = $model;

        return $this;
    }

}