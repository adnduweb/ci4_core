<?php

namespace Adnduweb\Ci4Core\Traits;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4Core\Entities\User;
use Adnduweb\Ci4Core\Models\UserModel;
use Adnduweb\Ci4Core\Models\CompanyModel;
use Adnduweb\Ci4Core\Models\FormModel;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait UuidTrait
{

    public function getIdUserByUUID()
    {
        $user = (new UserModel())->getIdUserByUUID($this->uuidUser);
        return $user->id;
    }

    public function getIdCompanyByUUID()
    {
        $company = (new CompanyModel())->getIdCompanyByUUID($this->uuidCompany);
        return $company->id;
    }

    public function getIdFormByUUID()
    {
        $form = (new FormModel())->getIdFormByUUID($this->uuidForm);
        return $form->id;
    }
}
