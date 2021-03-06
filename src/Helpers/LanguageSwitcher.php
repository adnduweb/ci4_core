<?php

namespace Adnduweb\Ci4Core\Helpers;

/*
https://github.com/woody180/Languag-switcher-for-codeigniter-4
// Set language base
LanguageSwitcher::set([
    ['code' => 'en', 'name' => 'English', 'default' => true],
    ['code' => 'de', 'name' => 'German', 'default' => false]
]);
// Reset language base
LanguageSwitcher::reset();
// Check active language
LanguageSwitcher::active();
// Switch languages
LanguageSwitcher::switch('de');
// List all languages
LanguageSwitcher::list();
*/


class LanguageSwitcher extends BaseLanguageSwitcher {
    private static $activeLang;
    private static $default;


    public static function set($languageList = null) {

        // Check if language list json is not exists
        if (!is_array($languageList)) die('argument must be type of array');
        if (file_exists(__DIR__."/langList.json")) die('language list already exist. now you can append it with \'LanguageSwitcher::append()\' method');
        if (empty($languageList)) die('You must provide languge code along with language name \'title\'');


        // Create and insert language lsit
        file_put_contents(__DIR__.'/langList.json', self::toJSON($languageList));
    }


    public static function reset() {
        if (file_exists(__DIR__.'/langList.json')) {
            unlink(__DIR__.'/langList.json');
        } else {
            die('nothing to reset');
        }
    }


    private static function toJSON($fileArray) {
        $json = json_encode($fileArray, JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        return $json;
    }


    public static function append($args) {
        $langList = file_get_contents(__DIR__.'/langList.json') ?? die('You must set language list first');

        $langList = json_decode($langList, true);

        $code = $args['code'];
        $name = $args['name'];
        $default = $args['default'];

        foreach ($langList as $key => $value) {
            if (strtolower($name) == strtolower($value['name'])) die($name . ' - already in list');
            if (strtolower($code) == strtolower($value['code'])) die($code . ' - already in list');
        }

        if ($default == 1) {
            foreach ($langList as $key => $value) $langList[$key]['default'] = 0;
        }

        $langList[] = $args;

        file_put_contents(__DIR__.'/langList.json', self::toJSON($langList));
    }


    public static function list() {
        if (file_exists(__DIR__.'/langList.json')) {
            $file = file_get_contents(__DIR__.'/langList.json');
            $list = json_decode($file, true);
            return $list;
        } else {
            die('You must set language list first');
        }
    }


    public static function active() {
        //if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (session()->get('lang')) {
            $list = self::list();
            foreach ($list as $key => $value) {
                if ( strtolower($value['code']) == strtolower(session()->get('lang')) ) {
                    return session()->get('lang');
                }
            }

            session()->set('lang', self::default()['code']);
            return session()->get('lang');
        } else {
            session()->set('lang', self::default()['code']);
            return session()->get('lang');
        }
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


    public static function default() {
        if (file_exists(__DIR__.'/langList.json')) {
            $list = self::list();
            foreach ($list as $key => $value) if ($value['default'] == 1) return $value;
        } else {
            die('You must set language list first');
        }
    }


    public static function isset() {
        if (file_exists(__DIR__.'/langList.json')) {
            return 1;
        } else {
            return 0;
        }
    }


    public static function translate(array $languages) {        
        return $languages[self::active()];
    }

}