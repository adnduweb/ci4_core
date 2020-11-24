<?php

namespace Adnduweb\Ci4Core\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Traits\Date;
use DateTimeInterface;

trait BaseModel
{
    /**
     * Attributes from database fields.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The changed model attributes.
     *
     * @var array
     */
    protected $changes = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = true;


    /***
     *
     * ON cronstruit la liste des tables en dynamique
     */

    public function getBaseAllList(int $page, int $perpage, array $sort, array $query, $search = null)
    {
        $this->builder->select();
        if ($this->useTimestamps == true) {
            $this->builder->select('created_at as date_create_at');
        }
        if($this->tableLang == true)
            $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {

            // On recherches les colonnes
            $like = '';
            if (is_array($search) && !empty($search)) {

                $getLike = implode(',', $search);
                if (count($search) > 1)
                    $getLike = $getLike . ',';
                $like = str_replace(',', ' LIKE "%' . trim($query[0]) . '%" OR ', $getLike);
                $like = ' (' . $like . ') ';
                $like = str_replace(' OR )', ')', $like);
            }


            if ($this->useSoftDeletes == true) {
                $this->builder->where('deleted_at IS NULL');
            }
            $this->builder->where($like);

            if ($this->tableLang == true) {
                $this->builder->where('id_lang = ' . service('LanguageOverride')->getIdLocale());
            }

            $this->builder->limit(0, $page);
        } else {

            if ($this->useSoftDeletes == true) {
                $this->builder->where('deleted_at IS NULL');
            }

            if ($this->tableLang == true) {
                $this->builder->where('id_lang = ' . service('LanguageOverride')->getIdLocale());
            }

            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->builder->limit($perpage, $page);
        }

        $this->builder->orderBy($sort['field'] . ' ' . $sort['sort']);
        //echo $this->builder->getCompiledSelect(); exit;

        $productResult = $this->builder->get()->getResult();
        return $productResult;
    }

    /**
     *
     * Le nombres d'éléments
     */
    public function getAllCount(array $sort, array $query, array $search)
    {
        $this->builder->select($this->table . '.' . $this->primaryKey);
        if($this->tableLang == true)
            $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {

            // On recherches les colonnes
            $like = '';
            if (is_array($search) && !empty($search)) {

                $getLike = implode(',', $search);
                if (count($search) > 1)
                    $getLike = $getLike . ',';
                $like = str_replace(',', ' LIKE "%' . trim($query[0]) . '%" OR ', $getLike);
                $like = ' (' . $like . ') ';
                $like = str_replace(' OR )', ')', $like);
            }
            if ($this->useSoftDeletes == true) {
                $this->builder->where('deleted_at IS NULL');
            }

            $this->builder->where($like);

            if ($this->tableLang == true) {
                $this->builder->where('id_lang = ' . service('LanguageOverride')->getIdLocale()); 
            }
        } else {

            if ($this->useSoftDeletes == true) {
                $this->builder->where('deleted_at IS NULL');
            }

            if ($this->tableLang == true) {
                $this->builder->where('id_lang = ' . service('LanguageOverride')->getIdLocale());
            }
        }

        $this->builder->orderBy($sort['field'] . ' ' . $sort['sort']);
        $pages = $this->builder->get();
        //echo $this->builder->getCompiledSelect(); exit;
        return $pages->getResult();
    }


    /**
     *
     * On cherche si il y a des langues de dispos ou pas
     */
    public function getLanguesDispo(int $id, int $id_lang)
    {
        $this->builder_lang->select($this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder_lang->where('id_lang = ' . $id_lang . ' AND ' . $this->tableLang . '.' . $this->primaryKeyLang . ' = ' . $id);
        return (!empty($this->builder_lang->get()->getRow())) ? true : false;
    }

    /**
     *
     * On cherchce si le slug demandé existe dans la langue demandée
     */

    public function getIdPageBySlug($slug)
    {
        $this->builder->select($this->table . '.' . $this->primaryKey . ', active');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND slug="' . $slug . '" AND id_lang="' . service('LanguageOverride')->getIdLocale() . '"');
        $page = $this->builder->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1')
                return $page;
        }
        return false;
    }
}
