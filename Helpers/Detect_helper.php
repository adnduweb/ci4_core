<?php

if (!function_exists('detectBrowser')) {
    /**
     * Detection du navigateur.
     *
     * @return string
     */
    function detectBrowser($html = true)
    {
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
            return strtolower(str_replace(['-', '', ' ', '.'], ['_'], $currentAgent))
            . ' version_' .  strtolower(str_replace(['-', '', ' ', '.'], ['_'], $agent->getVersion()))
            . ' ' . strtolower(str_replace(['-', '', ' ', '.'], ['_'], $agent->getPlatform())) . ' ' . $support;
        } else {
            return $agent;
        }
    }
}