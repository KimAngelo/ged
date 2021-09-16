<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserCompany extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('user_company');
        $table->addColumn('id_user', 'integer')
            ->addColumn('id_company', 'integer')
            ->addTimestamps()
            ->addForeignKey('id_user', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('id_company', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
