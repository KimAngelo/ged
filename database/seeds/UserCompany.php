<?php


use Phinx\Seed\AbstractSeed;

class UserCompany extends AbstractSeed
{

    public function run()
    {
        $data = [
            'id_user' => 1,
            'id_company' => 1
        ];
        $user_company = $this->table('user_company');
        $user_company->insert($data)->saveData();
    }
}
