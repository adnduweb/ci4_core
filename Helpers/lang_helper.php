<?php

if (!function_exists('save_lang_file')) {
    /**
     * Save a language file.
     *
     * @param string  $filename The name of the file to locate. The file will be
     * found by looking in all modules.
     * @param string  $language The language to retrieve.
     * @param array   $settings An array of the language settings.
     * @param boolean $return   True to return the contents or false to write to
     * the file.
     * @param boolean $allowNewValues If true, new values can be added to the file.
     *
     * @return boolean|string False if there was a problem loading the file. Otherwise,
     * returns true when $return is false or a string containing the file's contents
     * when $return is true.
     */
    function save_all_lang_file($filename = null, $language = 'en', $settings = null, $return = false, $allowNewValues = false)
    {

        if (empty($filename) || !is_array($settings)) {
            return false;
        }
        $path = $orig_path = $filename;

        // Load the file and loop through the lines
        if (!is_file($orig_path)) {
            return false;
        }


        // print_r($settings); 
        $contents = file_get_contents($orig_path);
        $contents = trim($contents) . "\n";

        if (!is_file($path)) {
            // Create the folder...
            $folder = basename($path) == 'Language' ? "{$path}/{$language}" : dirname($path);
            if (!is_dir($folder)) {
                mkdir($folder);
                $path = basename($path) == 'Language' ? "{$folder}/{$module}_lang.php" : $path;
            }
        }

        // Save the file.
        $replace = '';
        foreach ($settings as $name => $val) {
            if ($val != '') {
                $val = '\'' . addcslashes($val, '\'\\') . '\'';
            }
            if ($val != '') {
                $replace .= "\t";
                $replace .= '\'' . addcslashes($name, '\'\\') . '\'';
                $replace .= ' => ';
                $replace .= $val . ',' . "\n";
            }
        }

        $replace = "<?php\n\n return [\n" . $replace . "];";
        $replace = $replace . "\n\n" . '/* write : ' . date('d/m/Y H:i:s') . ' */';
        //print_r($replace); exit;

        // Make sure the file still has the php opening header in it...
        if (strpos($contents, '<?php') === false) {
            $contents = "<?php\n\n{$contents}";
        }

        if ($return) {
            return $contents;
        }

        helper('filesystem');
        if (write_file($path, $replace, "w+")) {
            return true;
        }

        return false;
    }
}


if (!function_exists('search_text_lang')) {
    function search_text_lang($query, $language = 'en')
    {
        //echo  $query; exit;
        if (empty($query)) {
            return false;
        }

        $localefolder = APPPATH . "Language/" . $language . "/";
        $filestoexplore = array();

        foreach (scandir($localefolder) as $d) {
            if ($d == "." || $d == "..") continue;
            if (is_file($localefolder . $d) && pathinfo($localefolder . $d, PATHINFO_EXTENSION) == 'php') {
                $bundlename = str_replace(".php", "", $d);
                $filestoexplore[$d . "." . $bundlename] = $localefolder . $d;
            }
        }

        // print_r($filestoexplore);
        // exit;
        helper('string');
        $ret = array();
        $query = stringCleanUrl($query);
        // Recherche du texte
        foreach ($filestoexplore as $k => $f) {

            list($interface, $bundlename) = explode(".", $k);

            $data = include($f);

            $v = [];
            foreach ($data as $k => $d) {
                $value = $d;
                $match = strpos(strtolower($value), $query);
                if ($match !== false && $match >= 0) {
                    $v["info_lang"] = $language;
                    $v["info_key"] = $k;
                    $v["info_name"] = $value;
                    $v["info_interface"] = $interface;
                    $v["info_bundlename"] = $f;
                    $newkey = substr($k, 0, 5) . "_" . md5($interface . $f . $k);
                    $ret[$newkey] = $v;
                } else {
                    $match = strpos(strtolower($k), $query);
                    if ($match !== false && $match >= 0) {
                        $v["info_lang"] = $language;
                        $v["info_key"] = $k;
                        $v["info_name"] = $value;
                        $v["info_interface"] = $interface;
                        $v["info_bundlename"] = $f;
                        $newkey = substr($k, 0, 5) . "_" . md5($interface . $f . $k);
                        $ret[$newkey] = $v;
                    }
                }
            }
        }

        //print_r($ret); exit;

        ksort($ret);
        return $ret;
    }
}
