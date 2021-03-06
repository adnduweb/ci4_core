<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class VisitModel extends Model
{
	protected $table      = 'visits';
	protected $primaryKey = 'id';

	protected $returnType = 'Adnduweb\Ci4Core\Entities\Visit';
	protected $useSoftDeletes = false;

	protected $allowedFields = [
		'session_id', 'user_id', 'ip_address', 'user_agent', 'views',
		'scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment',
	];

	protected $useTimestamps = true;

	protected $validationRules    = [
		'host'         => 'required',
		'path'         => 'required',
	];
	protected $validationMessages = [];
	protected $skipValidation     = false;

}
