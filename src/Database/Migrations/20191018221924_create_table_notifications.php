<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_notifications extends Migration
{
    public function up()
    {
        $fields = [
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'            => ['type' => 'BINARY', 'constraint' => 16, 'unique' => true],
            'event'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'type'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'notifiable_type' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'notifiable_id'   => ['type' => 'BIGINT'],
            'data'            => ['type' => 'TEXT'],
            'read_at'         => ['type' => 'DATETIME','null' => TRUE],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey('read_at');
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('notifications');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('notifications');
    }
}
