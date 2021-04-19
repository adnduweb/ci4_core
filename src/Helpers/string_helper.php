<?php

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