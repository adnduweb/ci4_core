<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4Core\Entities\Audit;

class AuditModel extends Model
{
    protected $table      = 'audits';
    protected $primaryKey = 'id';

    protected $returnType     = Audit::class;
    protected $localizeFile   = 'Adnduweb\Ci4Core\Models\AuditModel';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['source', 'source_id', 'user_id', 'event', 'summary', 'data', 'created_at'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $searchKtDatatable  = ['source', 'user_id', 'event', 'data'];
    protected $fieldEncode  = ['data'];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('audits');
    }
 
    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->builder->select();
        $this->builder->select('created_at as date_create_at');
        if (isset($query[0]) && is_array($query)) {
            
             // On recherches les colonnes
             $like = '';
             if (is_array($this->searchKtDatatable) && !empty($this->searchKtDatatable)) {
 
                 $getLike = implode(',', $this->searchKtDatatable);
                 if (count($this->searchKtDatatable) > 1)
                     $getLike = $getLike . ',';
                 $like = str_replace(',', ' LIKE "%' . trim($query[0]) . '%" OR ', $getLike);
                 $like = ' (' . $like . ') ';
                 $like = str_replace(' OR )', ')', $like);
             }
             $this->builder->where($like);
             $this->builder->limit(0, $page);

        } else {
            $this->builder->where('user_id != 0');
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->builder->limit($perpage, $page);
        }
        $this->builder->orderBy($sort['field'] . ' ' . $sort['sort']);

        $auditsRow = $this->builder->get()->getResult();

        //echo $this->builder->getCompiledSelect(); exit;
        return $auditsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->builder->select('id');
        if (isset($query[0]) && is_array($query)) {
            $this->builder->where('(source LIKE "%' . $query[0] . '%" OR event LIKE "%' . $query[0] . '%") AND user_id != 0');
        } else {
            $this->builder->where('user_id != 0');
        }

        $this->builder->orderBy($sort['field'] . ' ' . $sort['sort']);

        $users = $this->builder->get();
        return $users->getResult();
    }
}
