<?php

class Migration_Add_tasks extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true
				),
				'name' => array(
					'type' => 'VARCHAR',
					'constraint' => '191',
				),
				'createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
				'updatedAt DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
			)
		);

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('tasks');
	}

	public function down()
	{
		$this->dbforge->drop_table('tasks');
	}
}
