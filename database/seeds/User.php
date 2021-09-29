<?php


use Phinx\Seed\AbstractSeed;

class User extends AbstractSeed
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
        //Cria o primeiro usuário
        $data = [
            [
                'first_name' => 'Kim',
                'last_name' => 'Angelo',
                'email' => 'kim@g7company.com.br',
                'password' => passwd('12345678'),
                'phone' => '(43) 99654-0756',
                'occupation' => 'Prefeito',
                'status' => 1,
                'admin' => 'true',
                'roles' => '1;2;3;5',
                'companies' => '1'
            ],
            [
                'first_name' => 'Rildonely',
                'last_name' => 'Galiza',
                'email' => 'gedtec@outlook.com',
                'password' => passwd('12345678'),
                'phone' => '',
                'occupation' => 'Proprietário',
                'status' => 1,
                'admin' => 'true',
                'roles' => '1;2;3;5',
                'companies' => '1'
            ]
        ];
        $user = $this->table('users');
        $user->insert($data)->saveData();
    }
}
