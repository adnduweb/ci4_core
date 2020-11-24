<?php

namespace Adnduweb\Ci4Core\Entities;

use Michalsn\Uuid\UuidEntity;

class Company extends UuidEntity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \Adnduweb\Ci4Core\Traits\BuilderEntityTrait;
    protected $table          = 'companies';
    protected $tableLang      = 'companies_langs';
    protected $primaryKey     = 'id';
    protected $primaryKeyLang = 'company_id';
    protected $uuids          = ['uuid_company'];

    protected $datamap = [];

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
    ];

    public function getIdCompany()
    {
        return $this->attributes['id'] ?? null;
    }

    public function getRaisonSocial()
    {
        return $this->attributes['raison_social'] ?? null;
    }

    public function getCodeCompany()
    {
        return $this->attributes['uuid_company'] ?? null;
    }

    public function getEmail()
    {
        return $this->attributes['email'] ?? null;
    }

    public function getTelephoneFixe()
    {
        return $this->attributes['telephone_fixe'] ?? null;
    }
    public function getTelephoneMobile()
    {
        return $this->attributes['telephone_mobile'] ?? null;
    }

    public function getLocation()
    {

        return $this->attributes['code_postal'] . ' ' . $this->attributes['ville']  ?? null;
    }

    public function activate()
    {
        $this->attributes['active'] = 1;
        return $this;
    }
    public function deactivate()
    {
        $this->attributes['active'] = 0;

        return $this;
    }

    /**
     *
     * On sauvegarde la langue
     *
     */
    public function saveLang(array $dataLang, int $key)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);
        foreach ($dataLang as $k => $v) {
            $this->tableLangRes =  $builder->where(['id_lang' => $k, $this->primaryKeyLang => $key])->get()->getRow();
            // print_r($this->tableLangRes);
            if (empty($this->tableLangRes)) {
                $data = [
                    $this->primaryKeyLang => $key,
                    'id_lang'             => $k,
                    'bio'                => $v['bio']
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    $this->primaryKeyLang => $this->tableLangRes->{$this->primaryKeyLang},
                    'id_lang'             => $this->tableLangRes->id_lang,
                    'bio'                => $v['bio']
                ];
                $builder->set($data);
                $builder->where([$this->primaryKeyLang => $this->tableLangRes->{$this->primaryKeyLang}, 'id_lang' => $this->tableLangRes->id_lang]);
                $builder->update();
            }
        }
    }
}
