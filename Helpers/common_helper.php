<?php

/**
 * Common file
 *
 * PHP Version 1.0
 *
 * @category Common
 * @package  Common
 * @author   Fabrice Loru <contact@adnduweb.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://example.com/recipes
 */

use App\Models;
use Config\Services;
use CodeIgniter\CodeIgniter;


// -----------------------------------------------------------------------

/**
 * Add this to the top of your helper file.
 *
 * use CodeIgniter\CodeIgniter;
 *
 * USAGE:
 * <?= ciVersion();?>
 */
if (!function_exists('ciVersion')) {
    /**
     * ciVersion ()
     * -------------------------------------------------------------------
     *
     * @return string
     */
    function ciVersion(): string
    {
        return 'CodeIgniter version: ' . CodeIgniter::CI_VERSION;
    }
}



if (!function_exists('generer_mot_de_passe')) {
    function generer_mot_de_passe($nb_caractere = 12)
    {
        $mot_de_passe = '';
        $chaine         = 'abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789+@!$%?&';
        $longeur_chaine = strlen($chaine);
        for ($i = 1; $i <= $nb_caractere; $i++) {
            $place_aleatoire = mt_rand(0, ($longeur_chaine - 1));
            $mot_de_passe   .= $chaine[$place_aleatoire];
        }
        return $mot_de_passe;
    }
}

if (!function_exists('removeAccents')) {
    /**
     * Traitement de texte.
     *
     * @param string $txt
     *
     * @return string
     */
    function removeAccents($txt)
    {
        $txt = str_replace('œ', 'oe', $txt);
        $txt = str_replace('Œ', 'Oe', $txt);
        $txt = str_replace('æ', 'ae', $txt);
        $txt = str_replace('Æ', 'Ae', $txt);
        $txt = str_replace('?', '', $txt);
        $txt = str_replace('.', '', $txt);
        mb_regex_encoding('UTF-8');
        $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
        $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
        $txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
        $txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
        $txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
        $txt = mb_ereg_replace('[ďđ]', 'd', $txt);
        $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
        $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
        $txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
        $txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
        $txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
        $txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
        $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
        $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
        $txt = str_replace('Ĵ', 'J', $txt);
        $txt = str_replace('ĵ', 'j', $txt);
        $txt = str_replace('Ķ', 'K', $txt);
        $txt = str_replace('ķ', 'k', $txt);
        $txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
        $txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
        $txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
        $txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
        $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
        $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', 'o', $txt);
        $txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
        $txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
        $txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
        $txt = mb_ereg_replace('[śŝşš]', 's', $txt);
        $txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
        $txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
        $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
        $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
        $txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
        $txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
        $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
        $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
        $txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
        $txt = mb_ereg_replace('[źżž]', 'z', $txt);
        return $txt;
    }
}

if (!function_exists('uniforme')) {
    /**
     * Traitement de texte.
     *
     * @param string $texte
     * @param string $sep
     *
     * @return string
     */
    function uniforme($texte, $sep = '-')
    {
        $texte = html_entity_decode($texte);
        $texte = removeAccents($texte);
        $texte = trim($texte);
        $texte = preg_replace('#[^a-zA-Z0-9.-]#', $sep, $texte);
        $texte = preg_replace('#-#', $sep, $texte);
        $texte = preg_replace('#_+#', $sep, $texte);
        $texte = preg_replace('#_$#', '', $texte);
        $texte = preg_replace('#^_#', '', $texte);
        $texte = preg_replace('/\s/', '', $texte);
        $texte = preg_replace('/\s\s+/', '', $texte);
        return strtolower($texte);
    }
}

if (!function_exists('stringClean')) {
    /**
     * "Nettoie" une chaine de caractères (enlève les accents et caractères spéciaux)
     *
     * @param string $string la chaine à nettoyer
     * @param string $strtocase si la chaine doit etre rendue en minuscule ('lower') ou majuscule ('upper')
     * @param string $preserve caractères additionnels à conserver
     * @return string la chaine nettoyée
     */
    function stringClean($string, $strtocase = null, $preserve = '')
    {
        $unsafe = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å',
            'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë',
            'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø',
            'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü',
            'ÿ', 'Ñ', 'ñ', 'Ç', 'ç',
            ' ', '"', '\''
        );
        $safe = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a',
            'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e',
            'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i',
            'O', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o',
            'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u',
            'y', 'N', 'n', 'C', 'c',
            '-', '', ''
        );

        $retour = str_replace($unsafe, $safe, $string);
        //$retour = preg_replace('%[^a-zA-Z0-9'.$preserve.']+%','-',$retour);
        $retour = preg_replace('%^-|-$%', '', $retour);

        if ($strtocase == 'lower') {
            $retour = strtolower($retour);
        }
        if ($strtocase == 'upper') {
            $retour = strtoupper($retour);
        }
        return $retour;
    }
}

if (!function_exists('stringCleanUrl')) {
    /**
     * "Nettoie" une chaine de caractères pour la réécriture d'url (enlève les accents et caractères spéciaux)
     *
     * @param string $string la chaine à nettoyer
     * @return string la chaine nettoyée
     */
    function stringCleanUrl($string)
    {
        $retour = stringClean($string, "lower");

        $unsafe = array('_', ' ');
        $safe = array('-', '-');
        $retour = str_replace($unsafe, $safe, $retour);
        $retour = preg_replace("/&[a-z]+;/", "-", $retour);
        $retour = preg_replace("/[^\-a-zA-Z0-9]+/", "", $retour);
        $retour = preg_replace('%^-|-$%', '', $retour);
        $retour = preg_replace('%(-)+%', "-", $retour);

        return rawurlencode(trim($retour));
    }
}

if (!function_exists('passwdGen')) {
    /**
     * Génaration d'un mot hexadecimal aléatoire.
     *
     * @param integer $length
     * @param string  $flag
     *
     * @return string
     */
    function passwdGen($length = 8, $flag = 'ALPHANUMERIC')
    {
        switch ($flag) {
            case 'NUMERIC':
                $str = '0123456789';
                break;
            case 'NO_NUMERIC':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        for ($i = 0, $passwd = ''; $i < $length; $i++) {
            $passwd .= substr($str, mt_rand(0, strlen($str) - 1), 1);
        }
        return $passwd;
    }
}

if (!function_exists('detectBrowser')) {
    /**
     * Detection du navigateur.
     *
     * @return string
     */
    function detectBrowser($html = true)
    {
        //$request = service('request');
        $agent   = service('request')->getUserAgent();

        $support = '';
        if ($agent->isBrowser()) {
            // $currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
            $currentAgent = $agent->getBrowser();
            $support      = 'sp_desktop';
        } elseif ($agent->isRobot()) {
            $currentAgent = $this->agent->robot();
            $support      = 'sp_robot';
        } elseif ($agent->isMobile()) {
            $currentAgent = $agent->getMobile();
            $support      = 'sp_mobile';
        } else {
            $currentAgent = 'Unidentified User Agent';
            $support      = 'sp_unknow';
        }

        if ($html === true) {
            return uniforme($currentAgent, '_') . ' version_' . uniforme($agent->getVersion()) . ' ' . uniforme($agent->getPlatform(), '_') . ' ' . $support;
        } else {
            return $agent;
        }
    }
}

if (!function_exists('randomHash')) {
    /****************************************************/
    /*
         Titre : Générer une chaine de caractère unique et aléatoire

         URL   : https://phpsources.net/code_s.php?id=87
         Auteur         : Administrateur
         Date edition   : 04 Nov 2004
    */
    /*****************************************************/
    //Générer une chaine de caractère unique et aléatoire
    function randomHash($car)
    {
        $string = '';
        $chaine = 'abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ123456789$&';
        srand((float) microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }
}



if (!function_exists('get_guid')) {
    // Get an RFC-4122 compliant globaly unique identifier
    function get_guid()
    {
        $data    = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

// Original: https://github.com/MicrosoftTranslator/Text-Translation-API-V3-PHP/blob/master/Translate.php#L26
if (!function_exists('com_create_guid')) {
    function com_create_guid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($time_ago)
    {
        $time_ago = strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
        $time     = time() - $time_ago;

        //echo $time;

        switch ($time):
                // seconds
            case $time <= 60:
                return lang('Admin.ago') . ' ' . lang('Admin.lessthan_a_minutes_ago');
                // minutes
            case $time >= 60 && $time < 3600:
                return (round($time / 60) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_minute') : lang('Admin.ago') . ' ' . round($time / 60) . ' ' . lang('Admin.minutes');
                // hours
            case $time >= 3600 && $time < 86400:
                return (round($time / 3600) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_hour') : lang('Admin.ago') . ' ' . round($time / 3600) . ' ' . lang('Admin.hours');
                // days
            case $time >= 86400 && $time < 604800:
                return (round($time / 86400) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_day') : lang('Admin.ago') . ' ' . round($time / 86400) . ' ' . lang('Admin.jours');
                // weeks
            case $time >= 604800 && $time < 2600640:
                return (round($time / 604800) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_week') : lang('Admin.ago') . ' ' . round($time / 604800) . ' ' . lang('Admin.weeks');
                // months
            case $time >= 2600640 && $time < 31207680:
                return (round($time / 2600640) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_month') : lang('Admin.ago') . ' ' . round($time / 2600640) . ' ' . lang('Admin.months');
                // years
            case $time >= 31207680:
                return (round($time / 31207680) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_year') : lang('Admin.ago') . ' ' . round($time / 31207680) . ' ' . lang('Admin.years');

        endswitch;
    }
}

if (!function_exists('relative_time')) {
    /**
     * Return a string representing how long ago a given UNIX timestamp was,
     * e.g. "moments ago", "2 weeks ago", etc.
     *
     * @todo Consider updating this to use date_diff() and/or DateInterval.
     * @todo Internationalization.
     *
     * @param integer $timestamp A UNIX timestamp.
     *
     * @return string A human-readable amount of time 'ago'.
     */
    function relative_time($time)
    {
        if ($time !== '' && !is_int($time)) {
            $time = strtotime($time);
        }
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff = time() - $time;

        // Time difference in seconds
        $sec = $diff;

        // Convert time difference in minutes
        $min = round($diff / 60);

        // Convert time difference in hours
        $hrs = round($diff / 3600);

        // Convert time difference in days
        $days = round($diff / 86400);

        // Convert time difference in weeks
        $weeks = round($diff / 604800);

        // Convert time difference in months
        $mnths = round($diff / 2600640);

        // Convert time difference in years
        $yrs = round($diff / 31207680);

        // Check for seconds
        if ($sec <= 60) {
            echo "$sec seconds ago";
        }

        // Check for minutes
        elseif ($min <= 60) {
            if ($min === 1) {
                echo lang('Core.an_minute_ago', [$hrs]);
            } else {
                echo lang('Core.minutes_ago', [$min]);
            }
        }

        // Check for hours
        elseif ($hrs <= 24) {
            if ($hrs === 1) {
                echo lang('Core.an_hour_ago', [$hrs]);
            } else {
                echo lang('Core.hours_ago', [$hrs]);
            }
        }

        // Check for days
        elseif ($days <= 7) {
            if ($days === 1) {
                echo 'Yesterday';
            } else {
                echo "$days days ago";
            }
        }

        // Check for weeks
        elseif ($weeks <= 4.3) {
            if ($weeks === 1) {
                echo 'a week ago';
            } else {
                echo "$weeks weeks ago";
            }
        }

        // Check for months
        elseif ($mnths <= 12) {
            if ($mnths === 1) {
                echo 'a month ago';
            } else {
                echo "$mnths months ago";
            }
        }

        // Check for years
        else {
            if ($yrs === 1) {
                echo 'one year ago';
            } else {
                echo "$yrs years ago";
            }
        }
    }
    if (!function_exists('encryptUrl')) {
        function encryptUrl($name)
        {
            return sha1($name . $_SERVER['REQUEST_URI']);
        }
    }
}


if (!function_exists('minify_html')) {
    /**
     * -----------------------------------------------------------------------------------------
     * Based on `https://github.com/mecha-cms/mecha-cms/blob/master/system/kernel/converter.php`
     * -----------------------------------------------------------------------------------------
     */
    // HTML Minifier
    function minify_html($input)
    {
        if (trim($input) === '') {
            return $input;
        }
        // Remove extra white-space(s) between HTML attribute(s)
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function ($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", '', $input));
        // Minify inline CSS declaration(s)
        if (strpos($input, ' style=') !== false) {
            $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function ($matches) {
                return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
            }, $input);
        }
        if (strpos($input, '</style>') !== false) {
            $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function ($matches) {
                return '<style' . $matches[1] . '>' . minify_css($matches[2]) . '</style>';
            }, $input);
        }
        if (strpos($input, '</script>') !== false) {
            $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function ($matches) {
                return '<script' . $matches[1] . '>' . minify_js($matches[2]) . '</script>';
            }, $input);
        }
        return preg_replace([
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s',
        ], [
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            '',
        ], $input);
    }
}

if (!function_exists('arrayToArray')) {
    function arrayToArray(array $array)
    {
        $newArray = [];
        $i = 0;
        foreach ($array as $k => $v) {
            $newArray[$v['name']] = $v['value'];
            $i++;
        }
        return $newArray;
    }
}


if (!function_exists('replaceInfile')) {
    function replaceInfile($file, $key, $find, $replace)
    {
        if ($find != $replace) {
            //recupere la totalité du fichier
            $str = file_get_contents($file);
            if ($str === false) {
                return false;
            } else {
                $replace = addslashes($replace);
                //effectue le remplacement dans le texte
                $str = str_replace(" => '" . $find . "'", " => '" . $replace . "'", $str);

                if (preg_match('`\/* write : (.+)\ */`', $str, $intro)) {
                    //print_r($intro); exit;
                    $str = str_replace($intro[1], date('d/m/Y H:i:s') . ' *', $str);
                }

                //remplace dans le fichier
                if (file_put_contents($file, $str) === false) {
                    return false;
                }
            }
        }
        return true;
    }
}

/* Convert hexdec color string to rgb(a) string */
if (!function_exists('hex2rgba')) {
    function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}


if (!function_exists('ConvertisseurTime')) {
    function ConvertisseurTime($Time)
    {
        if ($Time < 3600) {
            $heures = 0;

            if ($Time < 60) {
                $minutes = 0;
            } else {
                $minutes = round($Time / 60);
            }

            $secondes = floor($Time % 60);
        } else {
            $heures = round($Time / 3600);
            $secondes = round($Time % 3600);
            $minutes = floor($secondes / 60);
        }

        $secondes2 = round($secondes % 60);

        $TimeFinal = $heures . 'h ' . $minutes . 'min ' . $secondes2 . 's';
        return $TimeFinal;
    }
}


// -----------------------------------------------------------------------

/**
 * now () method
 */
if (!function_exists('now')) {
    function now()
    {
        // uses the default timezone.
        return date_create('now')->format('Y-m-d H:i:s');
    }
}

// -----------------------------------------------------------------------

/**
 * nowTimeZone () method
 */
if (!function_exists('nowTimeZone')) {
    function nowTimeZone($timeZone)
    {
        // $timeZone format 'America/New_York'
        return date_create('now', timezone_open($timeZone))->format('Y-m-d H:i:s');
    }
}
