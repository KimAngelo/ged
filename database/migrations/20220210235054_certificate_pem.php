<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CertificatePem extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('companies');
        $table->addColumn('certificate_pem', 'string', ['null' => true, 'after' => 'report_total_pages'])
            ->update();
    }
}
