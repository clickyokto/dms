<?php

use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('supplier')->delete();
        
        \DB::table('supplier')->insert(array (
            0 => 
            array (
                'id' => 19,
                'branch_id' => NULL,
                'title' => 'Mr',
                'business_name' => 'ASK',
                'supplier_type' => 'S',
                'fname' => 'Ashan',
                'lname' => 'Silva',
                'nic' => '',
                'passport_no' => '',
                'passport_expire_date' => '',
                'telephone' => '',
                'mobile' => '+94711252283',
                'email' => '',
                'dob' => NULL,
                'gender' => '',
                'website' => '',
                'remarks' => '',
                'balance' => 0.0,
                'credit' => 0.0,
                'discount' => 0,
                'discount_type' => 'P',
                'status' => 'A',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2019-11-16 01:58:50',
                'updated_at' => '2019-11-16 01:58:50',
            ),
        ));
        
        
    }
}