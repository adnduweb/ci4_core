<?php

namespace Adnduweb\Ci4Core\Core;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;


class BaseCurrency
{

    protected $conversion_rates;

    protected $config;

    public function __construct(BaseConfig $config, ConnectionInterface $db = null)
    {
        // save configuration
        $this->config = $config;

        // initiate the Session library
        $this->session = Services::session();

        // If no db connection passed in, use the default database group.
        $db = db_connect($db);
        $this->builder = $db->table('currencies');
    }

    public function getCurrency()
    {

        $cacheKey = "currency-getCurrency";
        $content = cache($cacheKey);

        if ($content !== null)
            return $content;

        // Fetching JSON
        $response_json = @file_get_contents($this->config->req_url);
        if (empty($response_json)) {
            throw new \Exception(lang('Core.connexion_api_impossible'));
        }

        // Continuing if we got a result
        if (false !== $response_json) {

            // Try/catch for json_decode operation
            try {
                // Decoding
                $response = json_decode($response_json);
                //Error
                if ('error' === $response->result) {
                    if ($this->config->silent) :
                        return null;
                    else :
                        throw new \Exception($response->error);
                    endif;
                }
                // Check for success
                if ('success' === $response->result) {
                    //return $response->conversion_rates;
                    return $this->cache($cacheKey, $response->conversion_rates);
                }
            } catch (\Exception $e) {
                // Handle JSON parse error...
                if ($this->config->silent) :
                    return null;
                else :
                    throw new \Exception($e->getMessage());
                endif;
            }
        }
    }

    public function save()
    {
       
        $this->conversion_rates =  $this->getCurrency();

        $currencyModel = new \Adnduweb\Ci4Core\Models\CurrencyModel;
        $currrency = $currencyModel->findAll();

        if (!empty($currrency)) {
            foreach ($currrency as $cur) {
                $this->builder->set('conversion_rate', $this->conversion_rates->{$cur->iso_code}, FALSE);
                $this->builder->where('iso_code', $cur->iso_code);
                $this->builder->update();
            }
            return true;
        }
    }

    // try to cache a setting and pass it back
    protected function cache($key, $content)
    {
        if ($content === null)
            return cache()->delete($key);

        if ($duration = $this->config->cacheDuration)
            cache()->save($key, $content, $duration);
        return $content;
    }

    public function getDevise()
    {
        //Je recuprere la langue en cours
        $langueCurrent = service('request')->getLocale();

        //Devise par default
        $devise = 'setting_devise_default';

        // Le mutilangue est activé. 
        if(service('settings')->setting_activer_multilangue == true){
            $devise = 'setting_devise_' . $langueCurrent;
        }

        // Je recupere la devise en fct de la langue
       
        $currency = (new \Adnduweb\Ci4Core\Models\CurrencyModel())->find(service('settings')->{$devise});
        return $currency;
    }
}
