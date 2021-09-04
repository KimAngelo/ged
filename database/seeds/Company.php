<?php


use Phinx\Seed\AbstractSeed;

class Company extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        //Inserir primiera empresa de teste
        $data = [
            'name' => 'Prefeitura de Londrina',
            'document' => '75.771.477/0001-70',
            'manager' => 'Kim Angelo',
            'type' => 'Prefeitura',
            'address' => 'Av. Duque de Caxias, 635 – Bairro:Jd. Mazei II – Londrina - PR'
        ];
        $company = $this->table('companies');
        $company->insert($data)->saveData();
    }
}
