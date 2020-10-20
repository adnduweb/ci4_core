<?php namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
	protected $table      = 'settings';
	protected $primaryKey = 'id';

	protected $returnType     = 'object';
	protected $localizeFile   = 'Adnduweb\Ci4_page\Models\SettingModel';
	protected $useSoftDeletes = true;

	protected $allowedFields = ['name', 'scope', 'content', 'summary', 'protected'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;

}
