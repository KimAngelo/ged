<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Legislation extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('legislations');
        $table->addColumn('type', 'string')
            ->addColumn('number', 'string')
            ->addColumn('ementa', 'string')
            ->addColumn('date', 'date')
            ->addColumn('document_name', 'string')
            ->addColumn('total_page', 'integer')
            ->addColumn('id_company', 'integer')
            ->addForeignKey('id_company', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
