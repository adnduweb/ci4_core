<?php

namespace Adnduweb\Ci4Core\Traits;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4Core\Entities\Module;
use Adnduweb\Ci4Core\Models\ModuleModel;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait ModuleTrait
{

    public $compoments = [];

    public $compoment = [];

    public $builder = true;

    public function getIdModule()
    {
        $moduleModel = new ModuleModel();
        return $moduleModel->getIdModule($this->controller);
    }
}
