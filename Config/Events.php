<?php 

namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Config\Config;
use CodeIgniter\Events\Events;
use Adnduweb\Ci4Core\Core\BaseWhoopsHandler;

/**
 * Detect event on pre system.
 */
Events::on('pre_system', function () {
    if (ENVIRONMENT !== 'production') {
        $config = Config::get('Whoops')->settings;
        $whoops = new BaseWhoopsHandler($config);
        $whoops->run();
    }
});


Events::on('post_system', function () {
	service('audits')->save();
});
