<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator',
            ]
        ];

        foreach ($roles as $role) {
            factory(Role::class)->create($role);
        }
    }
}
