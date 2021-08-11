<?php


use Phinx\Seed\AbstractSeed;

class UserSeeders extends AbstractSeed
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
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'name' => $faker->userName,
                'email' => $faker->email,
                'password' => password_hash('12345678', PASSWORD_DEFAULT)
            ];
        }

        $users = $this->table('users');
        $users->insert($data)->save();
    }
}
