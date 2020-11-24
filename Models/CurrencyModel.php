<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class CurrencyModel extends Model
{
    use \Adnduweb\Ci4Core\Traits\AuditsTrait, \Adnduweb\Ci4Core\Models\BaseModel;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table           = 'currencies';
    protected $tableLang       = false;
    protected $primaryKey      = 'id';
    protected $returnType      = 'object';
    protected $localizeFile    = 'App\Models\CurrencyModel';
    protected $useSoftDeletes  = true;
    protected $allowedFields   = ['name', 'iso_code', 'symbol', 'numeric_iso_code', 'precision', 'conversion_rate', 'deleted', 'active'];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'name'            => 'required|is_unique[currencies.name,id,{id}]',
        'iso_code'        => 'max_length[255]',
        'conversion_rate' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $searchKtDatatable  = ['name', 'iso_code'];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('currencies');
    }


    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        return $this->getBaseAllList($page, $perpage, $sort, $query, $this->searchKtDatatable);
    }

}
