<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Convention extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('convention');
        $table->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY])
            ->addColumn('number', 'string')
            ->addColumn('object', 'string')
            ->addColumn('grantor', 'string', ['null' => true])
            ->addColumn('value', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
            ->addColumn('date', 'date', ['null' => true])
            ->addColumn('document_name', 'string')
            ->addColumn('total_page', 'integer')
            ->addColumn('id_company', 'integer')
            ->addForeignKey('id_company', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
