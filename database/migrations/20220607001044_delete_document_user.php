<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class DeleteDocumentUser extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('delete_document', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'after' => 'status'])
            ->update();
    }
}
