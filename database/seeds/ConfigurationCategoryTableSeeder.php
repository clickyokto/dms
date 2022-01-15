<?php

use Illuminate\Database\Seeder;

class ConfigurationCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('configuration_category')->delete();
        
        \DB::table('configuration_category')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'General',
                'description' => 'General',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-27 06:57:55',
                'updated_at' => '2019-09-27 06:57:55',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'System Configuration',
                'description' => '',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-10-17 04:01:33',
                'updated_at' => '2019-10-17 04:01:33',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Media Types',
                'description' => '',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-10-27 02:55:17',
                'updated_at' => '2019-10-27 02:55:17',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Vehicles',
                'description' => '',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-05 22:54:21',
                'updated_at' => '2019-11-05 22:54:21',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Communication',
                'description' => '',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-01-01 10:25:14',
                'updated_at' => '2020-01-01 10:25:14',
            ),
        ));
        
        
    }
}