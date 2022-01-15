<?php

use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branches')->delete();
        
        \DB::table('branches')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Main',
                'url' => 'http://localhost/hasinindu_rice_mill/public',
                'location' => 'main',
                'telephone' => NULL,
                'created_at' => '2020-04-30 00:00:00',
                'updated_at' => '2020-04-30 00:00:00',
            ),
        ));
        
        
    }
}