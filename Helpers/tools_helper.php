<?php 


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
