<?php

namespace Adnduweb\Ci4Core\Entities;

use CodeIgniter\Entity;

class Taxe extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \Adnduweb\Ci4Core\Traits\BuilderEntityTrait;
    protected $table      = 'taxes';
    protected $tableLang  = 'taxes_langs';
    protected $primaryKey = 'id';
    protected $primaryKeyLang = 'taxe_id';

    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];


    /**
     *
     * On sauvegarde la lang
     *
     * */
    public function saveLang(array $data, int $key)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);
        foreach ($data as $k => $v) {
            $this->tableLang =  $builder->where(['id_lang' => $k, $this->primaryKeyLang => $key])->get()->getRow();

            if (empty($this->tableLang)) {
                $data = [
                    $this->primaryKeyLang => $key,
                    'id_lang'             => $k,
                    'name'                => $v['name'],
                ];

                $builder->insert($data);
            } else {

                $data = [
                    $this->primaryKeyLang => $this->tableLang->{$this->primaryKeyLang},
                    'id_lang'             => $this->tableLang->id_lang,
                    'name'                => $v['name'],
                ];

                $builder->set($data);
                $builder->where([$this->primaryKeyLang => $this->tableLang->{$this->primaryKeyLang}, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }
}
