<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class LanguageModel extends Model
{
	use \Adnduweb\Ci4Core\Traits\AuditsTrait;
	protected $afterInsert = ['auditInsert'];
	protected $afterUpdate = ['auditUpdate'];
	protected $afterDelete = ['auditDelete'];

	protected $table              = 'langs';
	protected $primaryKey         = 'id';
	protected $returnType         = 'object';
	protected $localizeFile       = 'App\Models\LanguageModel';
	protected $useSoftDeletes     = true;
	protected $allowedFields      = ['name', 'active', 'iso_code', 'language_code', 'locale', 'date_format_lite', 'date_format_full'];
	protected $useTimestamps      = true;
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;
}
 