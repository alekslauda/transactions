<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 'admin')->create();
        factory(App\User::class)->create([
            'username' => 'user',
            'account_type' => 2
        ]);
        factory(App\User::class, 10)->create();
    }
}
