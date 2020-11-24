<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_tabs extends Migration
{
	public function up()
	{
		$fields = [
			'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'id_parent'  => ['type' => 'INT', 'constraint' => 11],
			'depth'      => ['type' => 'INT', 'constraint' => 11],
			'left'       => ['type' => 'INT', 'constraint' => 11],
			'right'      => ['type' => 'INT', 'constraint' => 11],
			'position'   => ['type' => 'INT', 'constraint' => 11],
			'section'    => ['type' => 'INT', 'constraint' => 11],
			'namespace'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
			'class_name' => ['type' => 'VARCHAR', 'constraint' => 255],
			'active'     => ['type' => 'INT', 'constraint' => 11],
			'icon'       => ['type' => 'TEXT'],
			'slug'       => ['type' => 'VARCHAR', 'constraint' => 255],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
			'updated_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id', true);
		$this->forge->addKey('created_at');
		$this->forge->addKey('updated_at');
		$this->forge->createTable('tabs');


		$fields = [
			'id_tab_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'tab_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
			'id_lang'     => ['type' => 'INT', 'constraint' => 11],
			'name'        => ['type' => 'VARCHAR', 'constraint' => 255]
		];
		$this->forge->addField($fields);
		$this->forge->addKey('id_tab_lang', true);
		$this->forge->addKey('id_lang');
		$this->forge->addForeignKey('tab_id', 'tabs', 'id', false, 'CASCADE');
		$this->forge->createTable('tabs_langs', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('tabs');
		$this->forge->dropTable('tabs_langs');
	}
}
