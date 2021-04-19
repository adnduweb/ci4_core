<?php

namespace Adnduweb\Ci4Core\Entities;

use CodeIgniter\Entity;

class Tab extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \Adnduweb\Ci4Core\Traits\BuilderEntityTrait;
    protected $table          = 'tabs';
    protected $tableLang      = 'tabs_langs';
    protected $primaryKey     = 'id';
    protected $primaryKeyLang = 'tab_id';
    /**
     * Maps names used in sets and gets against unique
     * names within the class, allowing independence from
     * database column names.
     *
     * Example:
     *  $datamap = [
     *      'db_name' => 'class_name'
     *  ];
     */
    protected $datamap = [];

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
    ];

    /**
     *
     * On sauvegarde la langue
     *
     */
    public function saveLang(array $data, int $key)
    {
        //print_r($data);
        $db      = \Config\Database::connect();
        $builder = $db->table('tabs_langs');
        foreach ($data as $k => $v) {
            $tabs_langs =  $builder->where(['id_lang' => $k, $this->primaryKeyLang => $key])->get()->getRow();
            // print_r($tabs_langs);
            if (empty($tabs_langs)) {
                $data = [
                    $this->primaryKeyLang => $key,
                    'id_lang'             => $k,
                    'name'                => $v['name'],
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    $this->primaryKeyLang => $tabs_langs->{$this->primaryKeyLang},
                    'id_lang'             => $tabs_langs->id_lang,
                    'name'                => $v['name'],
                ];
                print_r($data);
                $builder->set($data);
                $builder->where([$this->primaryKeyLang => $tabs_langs->{$this->primaryKeyLang}, 'id_lang' => $tabs_langs->id_lang]);
                $builder->update();
            }
        }
    }

    // public function getNameLang(int $id_lang)
    // {
    //     return $this->tabs_langs[$id_lang]->name ?? null;
    // }


    // public function _prepareLang()
    // {
    //     $lang = [];
    //     if (!empty($this->id)) {
    //         foreach ($this->attributes['tabs_langs'] as $tabs_langs) {
    //             $lang[$tabs_langs->id_lang] = $tabs_langs;
    //         }
    //     }
    //     return $lang;
    // }
}
