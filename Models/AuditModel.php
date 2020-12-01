<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table      = 'audits';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $localizeFile   = 'Adnduweb\Ci4Core\Models\AuditModel';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['source', 'source_id', 'user_id', 'event', 'summary', 'data', 'created_at'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $searchKtDatatable  = ['source', 'user_id', 'event'];

    public function __construct()
    {
        parent::__construct();
        $this->audits = $this->db->table('audits');
    }
 
    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->audits->select();
        $this->audits->select('created_at as date_create_at');
        if (isset($query[0]) && is_array($query)) {
            $this->audits->where('(source LIKE "%' . $query[0] . '%" OR event LIKE "%' . $query[0] . '%") AND user_id != 0');
            $this->audits->limit(0, $page);
        } else {
            $this->audits->where('user_id != 0');
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->audits->limit($perpage, $page);
        }
        $this->audits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->audits->get()->getResult();

        //echo $this->audits->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->audits->select('id');
        if (isset($query[0]) && is_array($query)) {
            $this->audits->where('(source LIKE "%' . $query[0] . '%" OR event LIKE "%' . $query[0] . '%") AND user_id != 0');
        } else {
            $this->audits->where('user_id != 0');
        }

        $this->audits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $users = $this->audits->get();
        return $users->getResult();
    }
}
