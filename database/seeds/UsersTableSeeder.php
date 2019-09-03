<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'group_id' => '1',
            'name' => 'Developer',
            'email' => 'scratch@booster.com',
            'email_verified_at' => new DateTime,
            'password' => bcrypt('scratch@booster.com'),
            'updated_at' => new DateTime,
            'created_at' => new DateTime,
        ]);
    }
}
