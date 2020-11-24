<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table        = 'visits';
    protected $primaryKey   = 'id';
    protected $returnType   = 'object';
    protected $localizeFile = 'App\Models\LogModel';
    

    public function __construct()
    {
        parent::__construct();
        $this->visits = $this->db->table('visits');
        $this->auth_logins = $this->db->table('auth_logins');
    }


    public function getAllTrafficList(int $page, int $perpage, array $sort, array $query)
    {
        $this->visits->select();
        $this->visits->select('created_at as date_create_at');
        $this->visits->select('updated_at as date_update_at');
        if (isset($query[0]) && is_array($query)) {
            $this->visits->where('(path LIKE "%' . $query[0] . '%" OR ip_address LIKE "%' . $query[0] . '%")');
            $this->visits->limit(0, $page);
        } else {
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->visits->limit($perpage, $page);
        }
        $this->visits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->visits->get()->getResult();

        //echo $this->visits->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllTrafficCount(array $sort, array $query)
    {
        $this->visits->select('id');
        if (isset($query[0]) && is_array($query)) {
            $this->visits->where('(path LIKE "%' . $query[0] . '%" OR ip_address LIKE "%' . $query[0] . '%")');
        }

        $this->visits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $users = $this->visits->get();
        return $users->getResult();
    }


    public function getAllConnexionsList(int $page, int $perpage, array $sort, array $query)
    {
        $this->auth_logins->select();
        $this->auth_logins->select('date as date_create_at');
        if (isset($query[0]) && is_array($query)) {
            $this->auth_logins->where('(email LIKE "%' . $query[0] . '%" OR ip_address LIKE "%' . $query[0] . '%")');
            $this->auth_logins->limit(0, $page);
        } else {
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->auth_logins->limit($perpage, $page);
        }
        $this->auth_logins->orderBy('date ' . $sort['sort']);

        $groupsRow = $this->auth_logins->get()->getResult();

        //echo $this->auth_logins->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllConnexionsCount(array $sort, array $query)
    {
        $this->auth_logins->select('id');
        if (isset($query[0]) && is_array($query)) {
            $this->auth_logins->where('(email LIKE "%' . $query[0] . '%" OR ip_address LIKE "%' . $query[0] . '%")');
        }

        $this->auth_logins->orderBy('date ' . $sort['sort']);

        $users = $this->auth_logins->get();
        return $users->getResult();
    }

    public function deleteConnexions(int $id)
    {
        $this->auth_logins->delete(['id' => $id]);
    }
}
