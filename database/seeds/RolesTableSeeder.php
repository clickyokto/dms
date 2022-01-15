<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'SUPER_ADMINISTRATOR',
                'display_name' => 'SUPER ADMINISTRATOR',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2019-10-26 11:33:13',
                'updated_at' => '2020-12-21 03:14:14',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'ADMINISTRATOR',
                'display_name' => 'Administrator',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2019-10-26 11:34:37',
                'updated_at' => '2020-05-07 16:37:30',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'MANAGER',
                'display_name' => 'Manager',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2019-10-26 15:40:00',
                'updated_at' => '2020-05-07 16:13:38',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'OFFICE_USER_WITH_MAXIMIZED_RIGHTS',
                'display_name' => 'Office User With Maximized Rights',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2019-11-13 02:11:45',
                'updated_at' => '2020-10-22 10:24:32',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'USER_WITH_LIMITED_RIGHTS',
                'display_name' => 'User with Limited Rights',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2020-08-07 15:47:15',
                'updated_at' => '2020-10-22 10:25:14',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'REPRESENTATIVE',
                'display_name' => 'Representative',
                'description' => '',
                'guard_name' => 'web',
                'created_at' => '2020-08-10 07:20:10',
                'updated_at' => '2020-10-22 10:25:48',
            ),
        ));
        
        
    }
}