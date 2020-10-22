<?php 

namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Database\ConnectionInterface;
use  Adnduweb\Ci4Core\Config\Thumbnails as ThumbnailsConfig;
use  Adnduweb\Ci4Core\Core\Thumbnails;

class Services extends BaseService
{
    public static function settings(BaseConfig $config = null, bool $getShared = true)
    {
		if ($getShared):
			return static::getSharedInstance('settings', $config);
		endif;

		// If no config was injected then load one
		// Prioritizes app/Config if found
		if (empty($config))
			$config = config('Settings');

		return new \Adnduweb\Ci4Core\Core\BaseSettings($config);
	}

	public static function audits(BaseConfig $config = null, bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('audits', $config);
		}

		// If no config was injected then load one
		if (empty($config)) {
			$config = config('Audits');
		}

		return new \Adnduweb\Ci4Core\Core\BaseAudits($config);
	}

	/**
	 * Returns an instance of the Thumbnails library
	 * using the specified configuration.
	 *
	 * @param ThumbnailsConfig|null $config
	 * @param boolean               $getShared
	 *
	 * @return Thumbnails
	 */
	public static function thumbnails(ThumbnailsConfig $config = null, bool $getShared = true): BaseThumbnails
	{
		if ($getShared)
		{
			return static::getSharedInstance('thumbnails', $config);
		}

		return new Thumbnails($config ?? config('Thumbnails'));
	}
}
