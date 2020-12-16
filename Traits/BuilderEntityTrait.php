<?php

namespace Adnduweb\Ci4Core\Traits;

/**
 *
 * Class par default des Entités
 *
 */

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4Core\Entities\Builder;
use Adnduweb\Ci4Core\Models\BuilderModel;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait BuilderEntityTrait
{


    /** 
     *
     * l'ID du module
     */
    public function getIdItem()
    {
        return $this->{$this->primaryKey} ?? null;
    }


    /** 
     *
     * Je viens d'ou 
     */
    public function getClassEntities()
    {
        return $this->table;
    }

    /** 
     *
     * Construction en tableau des langues
     */
    public function getNameAllLang()
    {
        $name = [];
        $i = 0;
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                $name[$lang->id_lang]['name'] = $lang->name;
                $i++;
            }
            return $name ?? null;
        } else {
            $name[service('LanguageOverride')->getIdLocale()]['name'] =  $this->attributes['name'];
            return $name ?? null;
        }
    }


    /** 
     * 
     * Titre 
     */
    public function getBName()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->name ?? null;
                }
            }
        } else {
            return $this->attributes['name'] ?? null;
        }
    }

    /** 
     * 
     * Titre 
     */
    public function getBTitle()
    {
       
        if (isset($this->{$this->tableLang})) { 
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->titre ?? null;
                }
            }
        } else {
            return $this->attributes['titre'] ?? null;
        }
    }

    /** 
     *
     * Deuxième titre 
     */
    public function getBSousName()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->name_2 ?? null;
                }
            }
        } else {
            return $this->attributes['name_2'] ?? null;
        }
    }

    /** 
     *
     * Description 
     */
    public function getBDescription()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->description ?? null;
                }
            }
        } else {
            return $this->attributes['description'] ?? null;
        }
    }

    /** 
     *
     * Description short 
     */
    public function getBDescriptionShort()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->description_short ?? null;
                }
            }
        } else {
            return $this->attributes['description_short'] ?? null;
        }
    }

    /** 
     *
     * Meta Description 
     */
    public function getBMetaDescription()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->meta_description ?? null;
                }
            }
        } else {
            return $this->attributes['meta_description'] ?? null;
        }
    }

    /**
     *
     * Meta title
     *
     */
    public function getBMetaTitle()
    {
        if (isset($this->{$this->tableLang})) {
            foreach ($this->{$this->tableLang} as $lang) {
                if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                    return $lang->meta_title ?? null;
                }
            }
        } else {
            return $this->attributes['meta_description'] ?? null;
        }
    }

    /***
     *
     * On prépare les différentes langues pour l'adminsitration
     *
     *
     */
    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->{$this->primaryKey})) {
            foreach ($this->{$this->tableLang} as $tableLang) {
                $lang[$tableLang->id_lang] = $tableLang;
            }
        }
        return $lang;
    }


    /***
     * 
     * Les liens sur le Front en fct de la langue
     */
    public function getLink()
    {
        if (isset($this->{$this->tableLang})) {
            if (!empty($this->{$this->primaryKey})) {
                foreach ($this->{$this->tableLang} as $lang) {
                    if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                        return $lang->slug ?? null;
                    }
                }
            }
        } else {
            return  $this->attributes['slug'] ?? null;
        }
    }

    /***
     *
     * Switch langue
     * Les liens sur le Front en fct de la langue 
     *
     */
    public function getStwichLangSlug()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);

        $lang = [];
        if (!empty($this->{$this->primaryKey})) {
            foreach ($this->{$this->tableLang} as $tabs_langs) {
                $lang[$tabs_langs->id_lang] = (object) ['slug' => $this->getRouteSwitch($tabs_langs->id_lang)];
                //getLinkPage($page, $v);
            }
        }
        return $lang;
    }

    /**
     * 
     * la liste des Builders sur le front
     * 
     * */

    public function getBuilders()
    {
        if (!empty($this->attributes['builders'])) {
            return $this->attributes['builders'];
        }
        return false;
    }


    /**
     * 
     * Demande d'un Builder en fct de son Id Field
     * 
     * */
    public function getBuilderItem(string $id_field)
    {
        foreach ($this->builders as $builder) {
            if ($id_field == $builder->id_field) {
                foreach ($builder->builders_langs as $lang) {
                    if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                        $builder->id_lang = $lang->id_lang;
                        $builder->content = $lang->content;
                    }
                }
                unset($builder->builders_langs);
                return $builder ?? null;
            }
            return false;
        }
    }

    /**
     * 
     * Demande d'un contenu Builder en fct de son Id Field
     * 
     * */

    public function getBuilderContent(string $id_field)
    {
        if (!empty($this->attributes['builder'])) {
            foreach ($this->builder as $builder) {
                if ($id_field == $builder->id_field) {
                    foreach ($builder->builder_langs as $lang) {
                        if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                            return $lang->content ?? null;
                        }
                    }
                }
                return null;
            }
            return null;
        }
    }

    /**
     * 
     * Demande d'un textera "Builder" en fct de son handle
     * 
     * */

    public function getTextarea(string $handle)
    {
        $textarea               = new \stdClass();
        $textarea->balise_id    = "";
        $textarea->balise_class = "";
        $textarea->options      = "";
        $textarea->settings     = "";
        $textarea->balise       = "";
        if (!empty($this->attributes['builders'])) {
            $i = 0;
            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "textareafield") {
                    foreach ($builder->builders_pages_langs as $lang) {
                        if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                            $textarea->balise_id    = $builder->balise_id;
                            $textarea->balise_class = $builder->balise_class;
                            $textarea->content      = $lang->content ?? null;
                            $textarea->options      = $builder->options;
                            $textarea->settings     = $builder->getAttrSettings();
                            return $textarea;
                        }
                    }
                }
                $i++;
            }
            return null;
        }
    }

    /**
     * 
     * Demande d'un Text "Builder" en fct de son handle
     * 
     * */

    public function getTitle(string $handle)
    {
        $title               = new \stdClass();
        $title->balise_id    = "";
        $title->balise_class = "";
        $title->options      = "";
        $title->settings     = "";
        $title->balise       = "";
        // print_r($this->attributes);
        // exit;
        if (!empty($this->attributes['builders'])) {
            $i = 0;
            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "textfield") {
                    $getAttrOptions = $builder->getAttrOptions();
                    if (!empty($getAttrOptions)) {
                        foreach ($builder->builders_pages_langs as $lang) {
                            if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
                                $title->balise_id    = $builder->balise_id;
                                $title->balise_class = $builder->balise_class;
                                $title->content      = $lang->content ?? null;
                                $title->balise       = $getAttrOptions->balise;
                                $title->options      = $builder->options;
                                $title->settings     = $builder->getAttrSettings();
                                return $title;
                            }
                        }
                    }
                }
                $i++;
            }
            return null;
        }
    }


    /**
     * 
     * Demande d'une Image "Builder" en fct de son handle
     * 
     * */
    public function getImage(string $handle)
    {
        $image = new \stdClass();
        if (!empty($this->attributes['builders'])) {
            $i = 0;

            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "imagefield") {

                    $getAttrOptions = $builder->getAttrOptions();
                    if (empty($getAttrOptions))
                        return $image;
                    //print_r($getAttrOptions);
                    $mediaModel = new \App\Models\MediaModel();
                    $image = $mediaModel->getMediaById($getAttrOptions->media->id, service('LanguageOverride')->getIdLocale());

                    if (empty($image)) {
                        $image = $mediaModel->where('id', $getAttrOptions->media->id)->get()->getRow();
                    }

                    //var_dump($image);
                    if (is_object($image)) {
                        //var_dump($image);
                        $image->balise_class          = $builder->balise_class;
                        $image->balise_id             = $builder->balise_id;
                        $image->options               = $getAttrOptions;
                        $image->settings              = $builder->getAttrSettings();
                        $image->options->media->class = 'adw_lazyload ';
                    }
                }
                $i++;
            }
        }
        if (!isset($image->uuid)) {
            $image = new \stdClass();
            $image->warning = '<pre><small> Attente - handle Image : ' . $handle . ' </small></pre>';
        }

        return $image;
    }

    /**
     * 
     * Demande d'une liste d'actu "Builder" en fct de son handle
     * 
     * */
    public function getBundleActu(string $handle)
    {

        $listActu = new \stdClass();
        $articles = [];

        if (!empty($this->attributes['builders'])) {
            $i = 0;

            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "actufield") {

                    $getAttrOptions = $builder->getAttrOptions();
                    if (empty($getAttrOptions))
                        return $listActu->options = $getAttrOptions;

                    $PostModel = new \Adnduweb\Ci4_blog\Models\PostModel();
                    $CategoryModel = new \Adnduweb\Ci4_blog\Models\CategoryModel();
                    //print_r($getAttrOptions); exit;
                    if ($getAttrOptions->cat == 'all') {
                        $articles = $PostModel->where('type', 1)->get()->getResult('array');
                    } else {
                        $articles = $PostModel->where(['type' => 1, 'id_category_default' => $getAttrOptions->cat])->get()->getResult('array');
                        // $listActu = $CategoryModel->where('id_media', $getAttrOptions->media->id_media)->get()->getRow();
                    }

                    //   print_r($articles); exit;

                    if (!empty($articles)) {
                        $i = 0;
                        foreach ($articles as $actu) {
                            $listActu->articles[$i] = new \Adnduweb\Ci4_blog\Entities\Post($actu);
                            $listActu->articles[$i]->categorie = $CategoryModel->find($actu['id_category_default']);
                            $i++;
                        }
                    }


                    if (is_object($listActu)) {
                        $listActu->class = $builder->class . ' actu ';
                        $listActu->id = $builder->id;
                        $listActu->options = $getAttrOptions;
                    }
                }
                $i++;
            }
        }
        return $listActu;
    }

    /**
     * 
     * Demande d'un diaporama "Builder" en fct de son handle
     * 
     * */
    public function getBundleDiaporama(string $handle)
    {

        $diaporama = new \stdClass();
        if (!empty($this->attributes['builders'])) {
            $i = 0;
            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "diaporamafield") {
                    $getAttrOptions = $builder->getAttrOptions();
                    if (empty($getAttrOptions))
                        return $diaporama->options = $getAttrOptions;

                    $diaporamaModel = new \Adnduweb\Ci4_diaporama\Models\DiaporamaModel();
                    $slideModel = new \Adnduweb\Ci4_diaporama\Models\SlideModel();
                    $original = $diaporamaModel->getDiaporamaFront($getAttrOptions->id, service('LanguageOverride')->getIdLocale());
                    if (!empty($original['originalSettings'])) {
                        $diaporama->settings = $original['originalSettings'];
                        $diaporama->settings->options = $getAttrOptions;
                        $getAttrSettings = $builder->getAttrSettings();
                        if (empty($getAttrOptions))
                            $diaporama->attr->settings = $getAttrSettings;
                        $i = 0;
                        $slides = [];
                        foreach ($original['originalSlides'] as $slide) {
                            $mediaModel = new \App\Models\MediaModel();
                            $getAttrOptions = $slide->getAttrOptions();
                            $idMedia = $getAttrOptions->media->id;
                            // print_r($getAttrOptions->media->id);
                            // exit;
                            if (!empty($getAttrOptions)) {

                                $pathinfo     = pathinfo($getAttrOptions->media->filename);
                                $diaporama->slides[$i]           = $getAttrOptions->media;
                                $diaporama->slides[$i]->id       = $builder->id;
                                $diaporama->slides[$i]->class    = $builder->class . ' adw_lazyload ';
                                $diaporama->slides[$i]->details  = $mediaModel->getMediaById($idMedia, service('LanguageOverride')->getIdLocale());
                                $diaporama->slides[$i]->basename = $pathinfo['basename'];
                                $dir = str_replace([site_url() . 'uploads/', '/' . $diaporama->slides[$i]->basename], '', $getAttrOptions->media->filename);
                                $diaporama->slides[$i]->dir      = ($dir == 'thumbnail') ?  $getAttrOptions->media->format : $dir;
                            }
                            $i++;
                        }
                    }
                }
                $i++;
            }
        }
        return $diaporama;
    }

    /**
     *
     * try to cache a setting and pass it back
     * gestion du cache des builders
     *
     * */
    protected function cache($key, $content)
    {
        if ($content === null) {
            return cache()->delete($key);
        }

        if ($duration = env('cache.CacheDuration')) {
            cache()->save($key, $content, $duration);
        }
        return $content;
    }

    /**
     *
     * Detections des routes
     *
     * */
    public function getRouteSwitch($id_lang)
    {

        switch ($this->getClassEntities()) {
            case 'b_posts':
                if (isset($this->id)) {
                    $slugArray = [];
                    $link_0 = (new \Adnduweb\Ci4_blog\Models\PostModel())->getLink($this->id, $id_lang);
                    if ($link_0) {
                        $slugArray[0] =  '/' . env('url.blog') . '/' . $link_0->slug;
                    }
                    asort($slugArray);
                    $slug = implode('/', $slugArray);
                    return $slug . env('app.suffix_url');
                }
                break;
            case 'b_categories':
                if (isset($this->id)) {
                    $slugArray = [];
                    $link_0 = (new \Adnduweb\Ci4_blog\Models\CategoryModel())->getLink($this->id, $id_lang);
                    if ($link_0) {
                        $slugArray[0] =  '/' . env('url.blog_cat') . '/' . $link_0->slug;
                    }
                    asort($slugArray);
                    $slug = implode('/', $slugArray);
                    return $slug . env('app.suffix_url');
                }
                break;
            case 'pages':
                if (isset($this->id)) {
                    $slugArray = [];
                    $link_0 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($this->id, $id_lang);
                    if ($link_0) {
                        $slugArray[0] =  '/' . $link_0->slug;
                        if ($link_0->id_parent != 0) {
                            $link_1 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_0->id_parent, $id_lang);
                            if ($link_1) {
                                $slugArray[1] = '/' . $link_1->slug;
                                if ($link_1->id_parent != 0) {
                                    $link_2 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_1->id_parent, $id_lang);
                                    if ($link_2) {
                                        $slugArray[2] = '/' . $link_2->slug;
                                        if ($link_2->id_parent != 0) {
                                            $link_4 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_2->id_parent, $id_lang);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (empty($slugArray))
                        return '/';


                    if ($slugArray[0] == '/-')
                        $slugArray[0] = str_replace("/-", '/', $slugArray[0]);

                    asort($slugArray);
                    $slug = implode('/', $slugArray);
                    //exit;
                    //return $this->getSlug($id_lang) . env('app.suffix_url');
                    return $slug . env('app.suffix_url');
                }
                break;
            default:
                return $this->getSlug($id_lang);
        }
    }


    // public function getNameLang()
    // {
    //     if (isset($this->{$this->tableLang})) {
    //         foreach ($this->{$this->tableLang} as $lang) {
    //             if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
    //                 return $lang->name ?? null;
    //             }
    //         }
    //     }
    // }

    // public function getNameAllLang()
    // {
    //     $name = [];
    //     $i = 0;
    //     if (isset($this->{$this->tableLang})) {
    //         foreach ($this->{$this->tableLang} as $lang) {
    //             $name[$lang->id_lang]['name'] = $lang->name;
    //             $i++;
    //         }
    //         return $name ?? null;
    //     } else {
    //         $name[service('LanguageOverride')->getIdLocale()]['name'] =  $this->attributes['name'];
    //         return $name ?? null;
    //     }
    // }

    // public function getSousNameLang()
    // {
    //     foreach ($this->{$this->tableLang} as $lang) {
    //         if (service('LanguageOverride')->getIdLocale() == $lang->id_lang) {
    //             return $lang->name_2 ?? null;
    //         }
    //     }
    // }


}
