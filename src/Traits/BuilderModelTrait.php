<?php

namespace Adnduweb\Ci4Core\Traits;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4Core\Entities\Builder;
use Adnduweb\Ci4Core\Models\BuilderModel;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait BuilderModelTrait
{

    public $compoments = [];

    public $compoment = [];

    public $builder = true;

    public function saveBuilder($builder)
    {

        $builderModel = new BuilderModel();
        // print_r($builder);
        // exit;
        if (!is_array($builder))
            return false;

        unset($builder['__field__']);
        $i = 0;
        $instance = [];
        foreach ($builder as $build) {
            $lang = (isset($build['lang'])) ? $build['lang'] : '';
            unset($build['lang']);
            $instance[] = $builderEntitie = new Builder($build);
            $builderEntitie->handle = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', preg_replace('/\s+/', '-', $builderEntitie->handle)));
            if ($builderEntitie->type == 'imagefield') {
                $getAttrOptions = $builderEntitie->getAttrOptions();

                if (!empty($getAttrOptions) || !is_null($getAttrOptions)) {

                    if (!in_array($getAttrOptions->media->format, ['thumbnail', 'small', 'medium', 'large', 'original'])) {
                        if (!strpos($getAttrOptions->media->filename, 'custom') === false) {
                            //     $oldName = pathinfo($getAttrOptions->media->filename);
                            //     $getAttrOptions->media->basename = $oldName['basename'];
                            //     $getAttrOptions->media->filename = base_url() . '/uploads/custom/' . $getAttrOptions->media->format;
                            //     $getAttrOptions->media->dir = $getAttrOptions->media->format = 'custom';
                            // } else {
                            $oldName = pathinfo($getAttrOptions->media->filename);
                            $getAttrOptions->media->basename = $oldName['basename'];
                            $getAttrOptions->media->dir = $getAttrOptions->media->format = 'custom';
                            if (isset($getAttrOptions->row_start))
                                $getAttrOptions->row_start = $getAttrOptions->row_start;
                            if (isset($getAttrOptions->row_end))
                                $getAttrOptions->row_end = $getAttrOptions->row_end;
                        }
                    } else {
                        $pathinfo     = pathinfo($getAttrOptions->media->filename);
                        $getAttrOptions->media->basename = $pathinfo['basename'];
                        $dir = str_replace([site_url() . 'uploads/', '/' . $getAttrOptions->media->basename], '', $getAttrOptions->media->filename);
                        $getAttrOptions->media->dir      = ($dir == 'thumbnail') ?  $getAttrOptions->media->format : $dir;
                        $getAttrOptions->media->filename = site_url() . 'uploads/' . $getAttrOptions->media->dir . '/' . $getAttrOptions->media->basename;
                        if (isset($getAttrOptions->row_start))
                            $getAttrOptions->row_start = $getAttrOptions->row_start;
                        if (isset($getAttrOptions->row_end))
                            $getAttrOptions->row_end = $getAttrOptions->row_end;
                    }
                    try {
                        $client = \Config\Services::curlrequest();
                        $response = $client->request('GET', $getAttrOptions->media->filename);
                        list($width, $height, $type, $attr) =  getimagesize($getAttrOptions->media->filename);
                        $getAttrOptions->media->dimensions = ['width' => $width, 'height' => $height];
                        $builderEntitie->options = json_encode($getAttrOptions);
                    } catch (\Exception $e) {
                        $builderEntitie->options = '';
                    }
                } else {
                    $builderEntitie->options = '';
                }
            } else {
                if (!empty($builderEntitie->options)) {
                    $builderEntitie->options = json_encode($builderEntitie->options);
                }
            }
            if (isset($build['settings'])) {
                $builderEntitie->settings = json_encode($builderEntitie->settings);
            } else {
                $builderEntitie->settings = '';
            }


            $builderEntitie->order = $i;
            if (!$builderModel->save($builderEntitie)) {
                throw DataException::forProblemSaving();
            }
            $id = (!isset($builderEntitie->id)) ? $builderModel->insertID() : $builderEntitie->id;
            if (!empty($lang)) {
                if ($builderEntitie->saveLang($lang, $id)) {
                    throw DataException::forProblemSaving();
                }
            }

            $i++;
        }
        //exit;

        return $instance;
    }

    public function getBuilderIdItem($id_page, int $idModule)
    {
        if (!is_null($id_page)) {
            $builderModel = new BuilderModel();
            //echo $id_page . ' -- ' . $idModule;  exit;
            return $builderModel->getBuilderIdItem((int) $id_page, $idModule);
        }
        return false;
    }

    public function deleteBuilder(int $id)
    {
        $builderModel = new BuilderModel();
        return $builderModel->delete(['id_builder' => $id]);
    }
}
