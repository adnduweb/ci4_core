<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4Core\Entities\Taxe;

class TaxeModel extends Model
{
    use \Adnduweb\Ci4Core\Traits\AuditsTrait, \Adnduweb\Ci4Core\Models\BaseModel;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table           = 'taxes';
    protected $tableLang       = 'taxes_langs';
    protected $with            = ['taxes_langs'];
    protected $without         = [];
    protected $primaryKey      = 'id';
    protected $primaryKeyLang  = 'taxe_id'; 
    protected $returnType      = Taxe::class;
    protected $localizeFile    = 'App\Models\TaxeModel';
    protected $useSoftDeletes  = true;
    protected $allowedFields   = ['rate', 'active'];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'rate'            => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $searchKtDatatable  = ['name', 'rate'];

    public function __construct()
    {
        parent::__construct();
        $this->builder       = $this->db->table('taxes');
        $this->taxes_langs = $this->db->table('taxes_langs');
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        return $this->getBaseAllList($page, $perpage, $sort, $query, $this->searchKtDatatable);
    }
}
