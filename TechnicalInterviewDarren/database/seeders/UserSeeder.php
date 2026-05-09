<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run()
    {
        User::create(['name'=>'IT Support','email'=>'support@helpdesk.com','password'=>Hash::make('password123'),'role'=>'support']);
        User::create(['name'=>'John Employee','email'=>'employee@helpdesk.com','password'=>Hash::make('password123'),'role'=>'employee']);
    }
}