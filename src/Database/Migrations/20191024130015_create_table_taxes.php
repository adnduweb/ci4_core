<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_taxe extends Migration
{
	public function up()
	{
		$fields = [
			'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'rate'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
			'active'     => ['type' => 'INT', 'constraint' => 11],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
			'updated_at' => ['type' => 'DATETIME', 'null' => true],
			'deleted_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id', true);
		$this->forge->addKey('created_at');
		$this->forge->addKey('updated_at');
		$this->forge->addKey('deleted_at');
		$this->forge->createTable('taxes');


		$fields = [
			'id_taxe_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'taxe_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
			'id_lang' => ['type' => 'INT', 'constraint' => 11],
			'name'    => ['type' => 'VARCHAR', 'constraint' => 255]
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id_taxe_lang', true);
		$this->forge->addKey('id_lang');
		$this->forge->addForeignKey('taxe_id', 'taxes', 'id', false, 'CASCADE');
		$this->forge->createTable('taxes_langs', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('taxes');
		$this->forge->dropTable('taxes_langs');
	}
}
