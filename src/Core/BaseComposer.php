<?php

namespace Adnduweb\Ci4Core\Core;

use Exception;
use Adnduweb\Ci4Core\Helpers\FileHelper;

abstract class BaseComposer extends \Composer\Installer\LibraryInstaller
{

    public static function postCreateProject($event)
    {
        static::runCommands($event, 'Adnduweb\Ci4Core\Core\BaseComposer::postCreateProject');
    }

    public static function postInstall($event)
    {
        static::runCommands($event, 'Adnduweb\Ci4Core\Core\BaseComposer::postInstall');
    }

    public static function postUpdate($event)
    {
        static::runCommands($event, 'Adnduweb\Ci4Core\Core\BaseComposer::postUpdate');
    }

    protected static function runCommands($event, $extraKey)
    {
        $params = $event->getComposer()->getPackage()->getExtra();

        if (isset($params[$extraKey]) && is_array($params[$extraKey]))
        {
            foreach ($params[$extraKey] as $method => $args)
            {
                call_user_func_array([__CLASS__, $method], (array) $args);
            }
        }
    }

    protected static function copy($files)
    {
        foreach($files as $source => $target)
        {
            try
            {
                FileHelper::copy($source, $target);
            }
            catch(Exception $e)
            {
                echo $e . PHP_EOL;
            }
        }
    }

    protected static function setPermission($files)
    {
        foreach($files as $file => $permission)
        {
            try
            {
                FileHelper::setPermission($file, $permission);
            }
            catch(Exception $e)
            {
                echo $e . PHP_EOL;
            }
        }
    }

}
