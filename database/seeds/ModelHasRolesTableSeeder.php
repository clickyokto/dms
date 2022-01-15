<?php

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('model_has_roles')->delete();
        
        \DB::table('model_has_roles')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 1,
            ),
            1 => 
            array (
                'role_id' => 1,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 5,
            ),
            2 => 
            array (
                'role_id' => 2,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 4,
            ),
            3 => 
            array (
                'role_id' => 2,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 6,
            ),
            4 => 
            array (
                'role_id' => 4,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 7,
            ),
            5 => 
            array (
                'role_id' => 7,
                'model_type' => 'Pramix\\XUser\\Models\\User',
                'model_id' => 8,
            ),
        ));
        
        
    }
}