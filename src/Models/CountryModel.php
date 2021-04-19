<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    use \Adnduweb\Ci4Core\Traits\AuditsTrait;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table      = 'countries';
    protected $primaryKey = 'id_country';
    protected $returnType = 'object';
    protected $localizeFile = 'App\Models\CountryModel';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id_lang', 'code_iso', 'name'];
    protected $useTimestamps = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function getAllCountry()
    {
        return $this->db->table($this->table)->where('id_lang', service('LanguageOverride')->getIdLocale())->orderBy('name ASC')->get()->getResult();
    }
}
