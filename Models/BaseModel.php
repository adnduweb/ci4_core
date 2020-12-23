<?php

namespace Adnduweb\Ci4Core\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Traits\Date;
use DateTimeInterface;
use CodeIgniter\Model;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $search = [];

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

    public function ktSearch( string $search){

        foreach ($this->getModels() as $class)
		{
            $instance = new $class();

            if(isset($instance->searchTable)){

                if(method_exists($instance, 'search')){

                    //print_r($instance); exit;
                    $resSearch = $instance->search($search);
                    $this->search[$class] = $resSearch;
                }
                
            }
            
        }

        return $this->search; 

    }


    /**
	 * Load model class names from all namespaces, filtered by group
	 *
	 * @return array of model class names
	 */
	protected function getModels(): array
	{
		$loader  = service('autoloader');
		$locator = service('locator');
		$models = [];

		// Get each namespace
		foreach ($loader->getNamespace() as $namespace => $path)
		{
			// Skip namespaces that are ignored
			if (in_array($namespace, Config('Admin')->ignoredNamespaces))
			{
				continue;
			}

			// Get files under this namespace's "/Models" path
			foreach ($locator->listNamespaceFiles($namespace, '/Models/') as $file)
			{
				if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == 'php')
				{
					// Load the file
					require_once $file;
				}
			}
		}

		// Filter loaded class on likely models
        $classes = preg_grep('/model$/i', get_declared_classes());
        
        //print_r($classes); exit;
		
		// Try to load each class
		foreach ($classes as $class)
		{
			// Check for ignored namespaces
			foreach (Config('Admin')->ignoredNamespaces as $namespace)
			{
				if (strpos($class, $namespace) === 0)
				{
					continue 2;
				}
            }

			// Make sure it's really a model
			if (! is_a($class, Model::class, true))
			{
                continue;
			}

			// Try to instantiate
			try
			{
				$instance = new $class();
			}
			catch (\Exception $e)
			{
                continue;
            }
           
			
			// Make sure it has a valid table
			$table = $instance->table;
			if (empty($table))
			{
				continue;
			}
			
			// Filter by group
			$group = $instance->DBGroup ?? $this->defaultGroup;
			if (empty($this->group) || $group == $this->group)
			{
				$models[] = $class;
			}
			unset($instance);
		}
		
		return $models;
	}

    /**
	 * Return a database object name without its prefix.
	 *
	 * @param string    $str  Name of a database object
	 *
	 * @return string   The updated name
	 */
	protected function stripPrefix(string $str): string
	{
		if (empty($str) || empty($this->prefix))
		{
			return $str;
		}

		// Strip the first occurence of the prefix
		return preg_replace("/^{$this->prefix}/", '', $str, 1);
    }
    
}
