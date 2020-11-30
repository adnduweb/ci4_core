<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4Core\Entities\Tab;

class TabModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait, \Adnduweb\Ci4Core\Traits\AuditsTrait, \Adnduweb\Ci4Core\Traits\NotificationsTrait;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table           = 'tabs';
    protected $with            = ['tabs_langs'];
    protected $without         = [];
    protected $primaryKey      = 'id';
    protected $primaryKeyLang  = 'tab_id';
    protected $returnType      = Tab::class;
    protected $localizeFile    = 'App\Models\TabModel';
    protected $useSoftDeletes  = false;
    protected $allowedFields   = ['id_parent', 'depth', 'left', 'right', 'position', 'section', 'namespace', 'class_name', 'active', 'icon', 'slug'];
    protected $useTimestamps   = true;
    protected $createdField    = 'created_at';
    protected $updatedField    = 'updated_at';
    protected $validationRules = [
        'class_name'      => 'required|is_unique[tabs.class_name,id,{id}]',
        'slug'            => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->tabs = $this->db->table('tabs');
        $this->tabs_langs = $this->db->table('tabs_langs');
    }

    public function getTab(): array
    {
        $contructArrayTab = [];
        $tab = $this->tabs->select()
            ->join('tabs_langs', 'tabs.id = tabs_langs.tab_id')
            ->where(['id_parent' => 0, 'id_lang' => service('LanguageOverride')->getIdLocale(), 'active' => 1])
            ->orderBy('left', 'ASC')
            ->get()->getResult();
        if (!empty($tab)) {
            $i = 0;
            foreach ($tab as $k) {
                $contructArrayTab[$i] = $k;
                //$contructArrayTab[$i]->name = $tabs_langs->name;
                //echo $k->id . ' ---  ' . $k->id_lang  . '<br/>';
                $children = $this->getChildren($k->id, $k->id_lang);
                //print_r($children);
                if (!empty($children)) {
                    $contructArrayTab[$i]->submenu = true;
                    $contructArrayTab[$i]->children = $children;

                    $s = 0;
                    foreach ($contructArrayTab[$i]->children as $kk) {
                        $children2 = $this->getChildren($kk->id, $kk->id_lang);
                        if (!empty($children2)) {
                            $contructArrayTab[$i]->children[$s]->submenu = true;
                            $contructArrayTab[$i]->children[$s]->children = $children2;
                        }
                        $s++;
                    }
                }
                $i++;
            }
        }
        return $contructArrayTab;
    }

    public function getChildren(int $id, int $id_lang): array
    {
        $tab = $this->tabs->select()
            ->join('tabs_langs', 'tabs.id = tabs_langs.tab_id')
            ->where(['id_parent' => $id, 'id_lang' => $id_lang, 'active' => 1])
            ->orderBy('left', 'ASC')
            ->get()->getResult();

        return $tab;
    }

    public function insertLang(array $data)
    {
        $builder = $this->db->table('tabs_langs');
        return $builder->insert($data);
    }
}
