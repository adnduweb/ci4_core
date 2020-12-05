<?php

namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Config\BaseConfig;

class Currency extends BaseConfig
{
	// key in $_SESSION that contains the integer ID of a logged in user
	public $sessionUserId = "logged_in";
	
	// number of seconds to cache a setting
	// 0 disables caching (not recommended except for testing)
	public $cacheDuration = 300;
	
	// whether to continue instead of throwing exceptions
	public $silent = false;

	public $req_url = "https://prime.exchangerate-api.com/v5/e19bcb5f34360800b176ee2b/latest/EUR";
}
