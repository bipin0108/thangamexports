<?php

use Illuminate\Database\Seeder;
use App\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User(); 
        $admin->first_name = 'Thangam';
		$admin->last_name = 'Export';
		$admin->email = 'admin@thangamexports.com';
        $admin->password = bcrypt('123456');
		$admin->is_admin = '1';
		$admin->save();     
    }
}
