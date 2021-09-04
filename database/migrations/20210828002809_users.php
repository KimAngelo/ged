<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        //create the table users
        $table = $this->table('users');
        $table->addColumn('first_name', 'string', ['limit' => 20])
            ->addColumn('last_name', 'string', ['limit' => 20])
            ->addColumn('email', 'string', ['limit' => 30])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('phone', 'string', ['limit' => 15, 'null' => true])
            ->addColumn('occupation', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true])
            ->addColumn('admin', 'string', ['limit' => 5])
            ->addColumn('roles', 'string')
            ->addColumn('companies', 'string')
            ->addTimestamps()
            ->create();
    }
}
