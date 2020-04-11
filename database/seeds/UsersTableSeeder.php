<?php

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
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
        factory(User::class, 100)->create();

        $admin = factory(User::class)->create([
            'name' => 'alex',
            'email' => 'alex@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $role = Role::where('name', 'admin')->firstOrFail();
        $admin->role()->associate($role)->save();
    }
}
