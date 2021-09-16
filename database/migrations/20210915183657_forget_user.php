<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ForgetUser extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('forget', 'string', ['null' => true, 'after' => 'companies'])
            ->update();
    }
}
