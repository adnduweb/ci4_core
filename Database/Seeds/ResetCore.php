<?php

namespace Adnduweb\Ci4Core\Database\Seeds;

class ResetCore extends \BasicApp\Core\Seeder
{

    public function run()
    {
        $this->disableForeignKeys();

        $this->db->table('audits')->truncate();
        $this->db->table('auth_activation_attempts')->truncate();
        $this->db->table('sp_auth_groups')->truncate();
        $this->db->table('sp_auth_groups_permissions')->truncate();
        $this->db->table('sp_auth_groups_users')->truncate();
        $this->db->table('sp_auth_logins')->truncate();
        $this->db->table('sp_auth_permissions')->truncate();
        $this->db->table('sp_auth_reset_attempts')->truncate();
        $this->db->table('sp_auth_tokens')->truncate();
        $this->db->table('sp_auth_users_permissions')->truncate();
        $this->db->table('sp_builders_pages')->truncate();
        $this->db->table('sp_builders_pages_langs')->truncate();

        $this->enableForeignKeys();
    }

}