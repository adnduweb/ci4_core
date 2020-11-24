<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_sessions_users extends Migration
{
    public function up()
    {

        $fields = [
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 128],
            'login_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 128],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'timestamp'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('login_id');
        $this->forge->addKey('session_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('login_id', 'auth_logins', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
        $this->forge->createTable('sessions_users', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('sessions_users');
    }
}
