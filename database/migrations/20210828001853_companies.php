<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Companies extends AbstractMigration
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
        //create the table companies
        $table = $this->table('companies');
        $table->addColumn('name', 'string', ['limit' => 40])
            ->addColumn('document', 'string', ['limit' => 18, 'null' => true])
            ->addColumn('manager', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('type', 'string', ['limit' => 20])
            ->addColumn('address', 'string', ['limit' => 100, 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
