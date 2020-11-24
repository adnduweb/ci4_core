<?php

namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4Core\Entities\Builder;

class BuilderModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4Core\Traits\AuditsTrait;
    protected $afterInsert        = ['auditInsert'];
    protected $afterUpdate        = ['auditUpdate'];
    protected $afterDelete        = ['auditDelete'];
    protected $table              = 'builders_pages';
    protected $tableLang          = 'builders_pages_langs';
    protected $with               = ['builders_pages_langs'];
    protected $without            = [];
    protected $primaryKey         = 'id';
    protected $primaryKeyLang     = 'builder_id';
    protected $returnType         = Builder::class;
    protected $localizeFile       = 'App\Models\BuilderModel';
    protected $useSoftDeletes     = false;
    protected $allowedFields      = ['id_module', 'id_item', 'id_field', 'handle', 'balise_class', 'balise_id', 'type', 'options', 'settings', 'order'];
    protected $useTimestamps      = true;
    protected $validationRules    = ['id_item' => 'required'];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->builders_pages       = $this->db->table('builders_pages');
        $this->builders_pages_langs = $this->db->table('builders_pages_langs');
    }

    public function getBuilderIdPage(int $idPage)
    {
        $instance = [];

        $this->builders_pages->select();
        $this->builders_pages->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builders_pages->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        $this->builders_pages->orderBy($this->table . '.' . $this->primaryKey . ' DESC');

        $builder = $this->builders_pages->get()->getResult();
        if (!empty($builder)) {
            foreach ($builder as $builder) {
                $instance[] = new Builder((array) $builder);
            }
        }
        return $instance;
    }

    public function getBuilderIdItem(int $id_page, int $idModule)
    {
        $instance = [];
        $this->builders_pages->select();
        $this->builders_pages->where('deleted_at IS NULL AND id_item=' . $id_page . ' AND id_module=' . $idModule . '');
        $this->builders_pages->orderBy($this->table . '.' . $this->primaryKey . ' DESC');
        $builder = $this->builders_pages->get()->getResult();
        if (!empty($builder)) {
            foreach ($builder as $builder) {
                $instance[] = new Builder((array) $builder);
            }
        }
        return $instance;
    }
}
