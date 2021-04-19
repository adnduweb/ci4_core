<?php

if (!function_exists('rrmdir')) {
    function rrmdir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}


if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
    {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!isset($dir_handle))
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}

if (!function_exists('custom_copy')) {
    function custom_copy($src, $dst)
    {

        // open the source directory 
        $dir = opendir($src);

        // Make the destination directory if not exist 
        @mkdir($dst, 0775, true);

        // Loop through the files in source directory 
        while ($file = readdir($dir)) {

            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {

                    // Recursively calling custom copy function 
                    // for sub directory  
                    custom_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }
}
