<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_country extends Migration
{
	public function up()
	{
		// settings
		$fields = [
			'id'         => ['type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'id_lang'    => ['type' => 'INT', 'constraint' => 5],
			'code_iso'   => ['type' => 'VARCHAR', 'constraint' => 3],
			'name'       => ['type' => 'VARCHAR', 'constraint' => 48],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
			'updated_at' => ['type' => 'DATETIME', 'null' => true],
			'deleted_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id', true);
		$this->forge->addKey('created_at');
		$this->forge->createTable('countries');
	}
	public function down()
	{
		$this->forge->dropTable('countries');
	}
}
