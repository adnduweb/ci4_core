<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_currencies extends Migration
{
	public function up()
	{
		// contact
		$fields = [
			'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'name'             => ['type' => 'VARCHAR', 'constraint' => 64],
			'iso_code'         => ['type' => 'VARCHAR', 'constraint' => 3],
			'symbol'           => ['type' => 'VARCHAR', 'constraint' => 10],
			'numeric_iso_code' => ['type' => 'VARCHAR', 'constraint' => 3],
			'precision'        => ['type' => 'INT', 'constraint' => 2],
			'conversion_rate'  => ['type' => 'DECIMAL', 'constraint' => '13,6'],
			'active'           => ['type' => 'TINYINT', 'constraint' => 1],
			'created_at'       => ['type' => 'DATETIME', 'null' => true],
			'updated_at'       => ['type' => 'DATETIME', 'null' => true],
			'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id', true);
		$this->forge->addKey('created_at');
		$this->forge->addKey('updated_at');
		$this->forge->createTable('currencies');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('currencies');
	}
}
