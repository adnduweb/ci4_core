<?php namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Events\Events;

Events::on('post_system', function () {
	service('audits')->save();
});
