<?php 

namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Session\SessionInterface;
use Adnduweb\Ci4Core\Models\LanguageModel;
use Adnduweb\Ci4Core\Config\Thumbnails as ThumbnailsConfig;
use Adnduweb\Ci4Core\Core\BaseVisits;
use Adnduweb\Ci4Core\Config\Settings as SettingsConfig;
use Adnduweb\Ci4Core\Models\SettingModel;
use Adnduweb\Ci4Core\Core\BaseSettings;

class Services extends BaseService
{
    public static function settings(SettingsConfig $config = null, SettingModel $model = null, SessionInterface $session = null, bool $getShared = true)
    {
		if ($getShared):
			return static::getSharedInstance('settings', $model, $session, $config);
		endif;

		return new BaseSettings(
			$config ?? config('Settings'),
			$model ?? model(SettingModel::class),
			$session ?? service('session')
		);
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
	public static function thumbnails(ThumbnailsConfig $config = null, bool $getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('thumbnails', $config);
		}

		return new \Adnduweb\Ci4Core\Core\BaseThumbnails($config ?? config('Thumbnails'));
	}


	public static function LanguageOverride(BaseConfig $config = null,  Model $LanguageModel = null, bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('LanguageOverride', $config, $LanguageModel);
		}

		$instance = new \Adnduweb\Ci4Core\Core\Language($config);

        if (empty($LanguageModel)) {
            $LanguageModel = new LanguageModel();
        }


		// If no config was injected then load one
		if (empty($config)) {
			$config = config('LanguageOverride');
		}

		return $instance->setLanguageModel($LanguageModel);
	}

	public static function visits(BaseConfig $config = null, bool $getShared = true)
    {
		if ($getShared):
			return static::getSharedInstance('visits', $config);
		endif;

		// If no config was injected then load one
		// Prioritizes app/Config if found
		if (empty($config))
			$config = config('Visits');

		return new BaseVisits($config);
	}

	public static function currency(BaseConfig $config = null, bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('currency', $config);
		}

		// If no config was injected then load one
		if (empty($config)) {
			$config = config('Currency');
		}

		return new \Adnduweb\Ci4Core\Core\BaseCurrency($config);
	}

	public static function getSecretKey(){
		return getenv('JWT_SECRET_KEY');
	} 

}
