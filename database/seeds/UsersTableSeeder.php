<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new User([
        'nombres' => 'Admin',
        'apellidos' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('123456'),
      ]);
      $admin->role = 'admin';

      $admin->save();

      $user = new User([
        'nombres' => 'User',
        'apellidos' => 'Apellidos',
        'email' => 'user@example.com',
        'password' => bcrypt('123456'),
      ]);

      $user->save();
    }
}
