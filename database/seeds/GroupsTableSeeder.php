<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => 'Super Admin Or Developer',
            'code' => 'developer',
            'permissions' => '{"profile":["change","password","signature"],"viewing_menu":["users","groups"]}',
            'description' => 'Super Admin Or Developer',
            'updated_at' => new DateTime,
            'created_at' => new DateTime,
        ]);
    }
}
