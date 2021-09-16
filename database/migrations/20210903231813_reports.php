<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Reports extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('reports');
        $table->addColumn('name', 'string')
            ->addColumn('year', 'integer', ['limit' => 4])
            ->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY])
            ->addColumn('document_name', 'string')
            ->addColumn('total_page', 'integer')
            ->addColumn('id_company', 'integer')
            ->addForeignKey('id_company', 'companies', 'id',['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
