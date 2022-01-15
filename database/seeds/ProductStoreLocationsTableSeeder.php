<?php

use Illuminate\Database\Seeder;

class ProductStoreLocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_store_locations')->delete();
        
        \DB::table('product_store_locations')->insert(array (
            0 => 
            array (
                'id' => 10,
                'branch_id' => 1,
                'location' => 'Weerawila Mill',
                'description' => '',
                'created_by' => 7,
                'updated_by' => 7,
                'deleted_at' => NULL,
                'created_at' => '2020-05-11 09:30:51',
                'updated_at' => '2020-05-11 09:30:51',
            ),
            1 => 
            array (
                'id' => 11,
                'branch_id' => 1,
                'location' => 'Debarawewa Mill',
                'description' => '',
                'created_by' => 7,
                'updated_by' => 7,
                'deleted_at' => NULL,
                'created_at' => '2020-05-11 09:31:06',
                'updated_at' => '2020-05-11 09:31:06',
            ),
            2 => 
            array (
                'id' => 12,
                'branch_id' => 1,
                'location' => 'Pannagamuwa Paddy Store',
                'description' => '',
                'created_by' => 7,
                'updated_by' => 7,
                'deleted_at' => NULL,
                'created_at' => '2020-05-11 09:31:36',
                'updated_at' => '2020-05-11 09:31:36',
            ),
            3 => 
            array (
                'id' => 13,
                'branch_id' => 1,
                'location' => 'Debarawewa Paddy Store',
                'description' => '',
                'created_by' => 7,
                'updated_by' => 7,
                'deleted_at' => NULL,
                'created_at' => '2020-05-11 09:32:13',
                'updated_at' => '2020-05-11 09:32:13',
            ),
            4 => 
            array (
                'id' => 14,
                'branch_id' => 1,
                'location' => 'Bags Store',
                'description' => '',
                'created_by' => 5,
                'updated_by' => 5,
                'deleted_at' => NULL,
                'created_at' => '2020-05-12 05:46:29',
                'updated_at' => '2020-05-12 05:46:29',
            ),
        ));
        
        
    }
}