<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class Expense
 */
final class Expense extends AbstractMigration
{

    /**
     * Tabela de despesas
     */
    public function change(): void
    {
        $table = $this->table('expense');
        $table->addColumn('number_expense', 'string', ['limit' => 10])
            ->addColumn('date', 'date')
            ->addColumn('favored', 'string')
            ->addColumn('source', 'string')
            ->addColumn('value', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('historical', 'text')
            ->addColumn('document_name', 'string')
            ->addColumn('total_page', 'integer')
            ->addTimestamps()
            ->create();
    }
}
