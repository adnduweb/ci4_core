<?php

namespace Adnduweb\Ci4Core\Helpers;

use Exception;

class FileHelper
{

    protected static function _returnFalse($error, $throwExceptions)
    {
        if ($throwExceptions)
        {
            throw new Exception($error, $throwExceptions);
        }

        return false;
    }

    /**
     * 
     */
    public static function setPermission($path, $permission, $throwExceptions = true, &$error = null)
    {
        if (is_file($path) || is_dir($path))
        {
            if (is_string($permission))
            {
                $permission = octdec($permission);
            }

            $result = chmod($path, $permission);

            if (!$result)
            {
                $error = $path . ' chmod ' . $permission . ' error.';
            
                return self::_returnFalse($error, $throwExceptions);
            }
        }
        else
        {
            $error = $path . ' path not found.';

            return self::_returnFalse($error, $throwExceptions);
        }

        return true;
    }

    public static function delete($dir, $throwExceptions = true, &$error = null)
    {
        if (is_file($dir) || is_link($dir))
        {
            if (!unlink($dir))
            {
                $error = 'Can\'t delete: ' . $dir;

                return self::_returnFalse($error, $throwExceptions);
            }

            return true;
        }

        if (!is_dir($dir))
        {
            return self::_returnFalse($error, $throwExceptions);
        }

        $items = self::readDirectory($dir, $throwExceptions, $error, $throwExceptions);

        if ($items === false)
        {
            return self::_returnFalse($error, $throwExceptions);
        }

        foreach($items as $file)
        {
            if (!self::delete($dir . DIRECTORY_SEPARATOR . $file, $error, $throwExceptions))
            {
                return self::_returnFalse($error, $throwExceptions);
            }
        }

        if (!rmdir($dir))
        {
            $error = 'Can\'t delete directory: '. $dir; 

            return self::_returnFalse($error, $throwExceptions);
        }

        return true;
    }    

    public static function createDirectory($path, $mode = 0775, $recursive = true, $throwExceptions = true, &$error = null)
    {
        if (is_dir($path))
        {
            return true;
        }

        $parentDir = dirname($path);
        
        if ($recursive && !is_dir($parentDir) && $parentDir !== $path)
        {
            $result = self::createDirectory($parentDir, $mode, true, $throwExceptions, $error, $throwExceptions);

            if (!$result)
            {
                return self::_returnFalse($error, $throwExceptions);
            }
        }
        
        try
        {
            if (!mkdir($path, $mode))
            {
                $error = $path . ' mkdir error.';

                self::_returnFalse($error, $throwExceptions);
            }
        }
        catch(Exception $e)
        {
            if (!is_dir($path))
            {
                $error = "Failed to create directory \"$path\": " . $e->getMessage();

                return self::_returnFalse($error, $throwExceptions);
            }
        }

        return self::setPermission($path, $mode, $throwExceptions, $error, $throwExceptions);
    }

    public static function copySymlink($source, $dest, $throwExceptions = true, &$error = null)
    {
        if (!is_link($source))
        {
            return self::_returnFalse($source . ' not symlink.', $throwExceptions);
        }

        if (!symlink(readlink($source), $dest))
        {
            $error = 'Can\'t create symlink from ' . $source . ' to ' . $dest;

            return self::_returnFalse($error, $throwExceptions);
        }

        return true;
    }

    public static function copyFile($source, $dest, $permission = 0755, $throwExceptions = true, &$error = null)
    {
        if (!is_file($source))
        {
            return self::_returnFalse($source . ' not file.', $throwExceptions);
        }

        $dir = pathinfo($dest, PATHINFO_DIRNAME);

        if (!$dir)
        {
            $error = 'Can\'t get PATHINFO_DIRNAME: ' . $dest;

            return self::_returnFalse($error, $throwExceptions);
        }

        if (!self::createDirectory($dir, $permission, true, $throwExceptions, $error, $throwExceptions))
        {
            return self::_returnFalse($error, $throwExceptions);
        }

        if (!copy($source, $dest))
        {
            $error = 'Can\'t copy file from ' . $source . ' to ' . $dest;

            return self::_returnFalse($error, $throwExceptions);
        }

        return true;
    }

    public static function copyDirectory($source, $dest, $permission = 0755, $throwExceptions, &$error = null)
    {
        if (!is_dir($dest))
        {        
            if (!self::createDirectory($dest, $permission, true, $throwExceptions, $error, $throwExceptions))
            {
                return self::_returnFalse($error, $throwExceptions);
            }
        }

        $items = self::readDirectory($source, $throwExceptions, $error, $throwExceptions);

        if ($items === false)
        {
            return self::_returnFalse($error, $throwExceptions);
        }

        foreach($items as $file)
        {
            if (!self::copy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file, $permission, $throwExceptions, $error, $throwExceptions))
            {
                return self::_returnFalse($error, $throwExceptions);
            }
        }

        return true;
    }

    public static function copy($source, $dest, $permission = 0755, $throwExceptions = true, &$error = null)
    {
        if (is_link($source))
        {
            return self::copySymlink($source, $dest);
        }

        if (is_file($source))
        {
            return self::copyFile($source, $dest, $permission, $throwExceptions, $error, $throwExceptions);
        }

        if (is_dir($source))
        {
            return self::copyDirectory($source, $dest, $permission, $throwExceptions, $error, $throwExceptions);
        }

        $error = 'File not found: ' . $source;

        return self::_returnFalse($error, $throwExceptions);
    }

    public static function readDirectory($source, $throwExceptions = true, &$error = null)
    {
        $dir = dir($source);

        if (!$dir)
        {
            $error = 'Can\'t open directory: ' . $dir;

            return self::_returnFalse($error, $throwExceptions);
        }

        $items = [];
        
        while(false !== ($file = $dir->read()))
        {
            if ($file == '.' || $file == '..')
            {
                continue;
            }

            $items[] = $file;
        }

        $dir->close();

        return $items;
    }

}