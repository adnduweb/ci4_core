<?php

namespace Adnduweb\Ci4Core\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_lang extends Migration
{
	public function up()
	{
		// settings
		$fields = [
			'id'          		=> ['type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'name'                  => ['type' => 'VARCHAR', 'constraint' => 32],
			'active'                => ['type' => 'TINYINT', 'constraint' => 1],
			'iso_code'              => ['type' => 'VARCHAR', 'constraint' => 2],
			'language_code'         => ['type' => 'VARCHAR', 'constraint' => 2],
			'locale'                => ['type' => 'VARCHAR', 'constraint' => 5],
			'date_format_lite'      => ['type' => 'VARCHAR', 'constraint' => 32],
			'date_format_full'      => ['type' => 'VARCHAR', 'constraint' => 32],
			'created_at'            => ['type' => 'DATETIME', 'null' => true],
			'updated_at'            => ['type' => 'DATETIME', 'null' => true],
			'deleted_at'            => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField($fields);
		$this->forge->addKey('id', true);
		$this->forge->addKey('name', false, true);
		$this->forge->addKey('created_at');
		$this->forge->createTable('langs');
	}
	public function down()
	{
		$this->forge->dropTable('langs');
	}
}
