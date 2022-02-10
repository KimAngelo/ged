<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SignedDocument extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('biddings');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();

        $table = $this->table('contracts');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();

        $table = $this->table('convention');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();

        $table = $this->table('expense');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();

        $table = $this->table('legislations');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();

        $table = $this->table('reports');
        $table->addColumn('signed', 'string', ['null' => true, 'limit' => 5, 'after' => 'id_company'])
            ->update();
    }
}
