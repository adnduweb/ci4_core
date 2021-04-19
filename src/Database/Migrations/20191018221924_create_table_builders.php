<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_builder extends Migration
{
    public function up()
    {

        /***** BUILDER ***********/
        $fields = [
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_module'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_item'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_field'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'handle'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'balise_class' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'balise_id'    => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => true],
            'type'         => ['type' => 'VARCHAR', 'constraint' => 128],
            'options'      => ['type' => 'TEXT', 'null' => true],
            'settings'     => ['type' => 'TEXT', 'null' => true],
            'order'        => ['type' => 'INT', 'constraint' => 11],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('builders_pages');


        $fields = [
            'id_builder_page_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'builder_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_lang'              => ['type' => 'INT', 'constraint' => 11],
            'content'              => ['type' => 'TEXT'],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_builder_page_lang', true);
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('builder_id', 'builders_pages', 'id', false, 'CASCADE');
        $this->forge->createTable('builders_pages_langs', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('builders_pages');
        $this->forge->dropTable('builders_pages_langs');
    }
}
