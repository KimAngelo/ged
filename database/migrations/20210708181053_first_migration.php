<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FirstMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        //REPORTS PARA RELÁTORIO DE ACESSOS
        $tab = $this->table('report_access');
        $tab->addColumn('users', 'integer')
            ->addColumn('views', 'integer')
            ->addColumn('pages', 'integer')
            ->addTimestamps()
            ->save();

        //RELATÓRIO DE USUÁRIOS ONLINE
        $tab = $this->table('report_online');
        $tab->addColumn('user', 'integer', ['null' => true])
            ->addColumn('ip', 'string', ['limit' => 50])
            ->addColumn('url', 'string')
            ->addColumn('agent', 'string')
            ->addColumn('pages', 'integer', ['default' => '1'])
            ->addTimestamps()
            ->save();

        //TABELA USUÁRIOS ADMIN
        $tab = $this->table('admin');
        $tab->addColumn('first_name', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('last_name', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('level', 'integer', ['default' => '5'])
            ->addTimestamps()
            ->save();

        //FILA DE E-MAILS
        $tab = $this->table('mail_queue');
        $tab->addColumn('subject', 'string')
            ->addColumn('body', 'string')
            ->addColumn('from_email', 'string')
            ->addColumn('from_name', 'string')
            ->addColumn('recipient_email', 'string')
            ->addColumn('recipient_name', 'string')
            ->addColumn('sent_at', 'timestamp')
            ->addTimestamps()
            ->save();


    }

    public function down()
    {
        $this->table('report_access')->drop()->save();
        $this->table('report_online')->drop()->save();
        $this->table('admin')->drop()->save();
        $this->table('mail_queue')->drop()->save();
    }
}
