<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Certificate extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('companies');
        $table->addColumn('certificate_crt', 'string', ['null' => true, 'after' => 'report_total_pages'])
            ->addColumn('certificate_pfx', 'string', ['null' => true, 'after' => 'report_total_pages'])
            ->addColumn('certificate_password', 'string', ['null' => true, 'after' => 'report_total_pages'])
            ->update();
    }
}
