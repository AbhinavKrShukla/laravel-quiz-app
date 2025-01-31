<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = "admin";
        $admin->email = "admin@gmail.com";
        $admin->email_verified_at = NOW();
        $admin->password = bcrypt("password");
        $admin->visible_password = "password";
        $admin->occupation = "CEO";;
        $admin->address = "Ranchi";
        $admin->phone = "321321321";
        $admin->is_admin = 1;
        $admin->save();
    }
}
