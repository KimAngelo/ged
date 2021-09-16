<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Companies extends AbstractMigration
{
    public function change(): void
    {
        //create the table companies
        $table = $this->table('companies');
        $table->addColumn('name', 'string', ['limit' => 40])
            ->addColumn('document', 'string', ['limit' => 18, 'null' => true])
            ->addColumn('manager', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('type', 'string', ['limit' => 20])
            ->addColumn('address', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('expense_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('expense_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('bidding_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('bidding_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('contract_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('contract_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('convention_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('convention_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('legislation_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('legislation_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('report_total_documents', 'integer', ['null' => true, 'after' => 'address'])
            ->addColumn('report_total_pages', 'integer', ['null' => true, 'after' => 'address'])
            ->addTimestamps()
            ->create();
    }
}
