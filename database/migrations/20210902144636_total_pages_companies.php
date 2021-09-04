<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class TotalPagesCompanies
 */
final class TotalPagesCompanies extends AbstractMigration
{

    /**
     * Adicionado campos para salvar o total de documentos e páginas
     */
    public function change(): void
    {
        $table = $this->table('companies');
        $table->addColumn('expense_total_documents', 'integer', ['null' => true, 'after' => 'address'])
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
            ->update();
    }
}
