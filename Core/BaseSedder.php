<?php

namespace Adnduweb\Ci4Core\Core;

class BaseSeeder extends \CodeIgniter\Database\Seeder
{

    public function disableForeignKeys()
    {
        $this->db->simpleQuery('SET FOREIGN_KEY_CHECKS = 0');
    }

    public function enableForeignKeys()
    {
        $this->db->simpleQuery('SET FOREIGN_KEY_CHECKS = 1');
    }

}