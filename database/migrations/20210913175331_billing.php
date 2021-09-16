<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Billing extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('biddings');
        $table->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY])
            ->addColumn('number_process', 'string', ['limit' => 10])
            ->addColumn('number_modality', 'string', ['limit' => 10, 'null' => true])
            ->addColumn('modality', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true])
            ->addColumn('date', 'date')
            ->addColumn('object', 'string')
            ->addColumn('document_name', 'string')
            ->addColumn('total_page', 'integer')
            ->addColumn('id_company', 'integer')
            ->addForeignKey('id_company', 'companies', 'id',['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
