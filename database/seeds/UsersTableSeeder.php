<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'branch_id' => 2,
                'username' => 'xSoniCx',
                'permission_type' => 'R',
                'fname' => 'Shamila',
                'lname' => 'Chathuranga',
                'telephone' => '+94702700948',
                'email' => 'Shamila.e.c.f@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$DEQURtXmDsZ71Z6yT5TxT.x9PnYI0aqKzHKqfSdW.dprlACqX/sOW',
                'status' => '1',
                'email_verified_at' => '2019-09-08 00:00:00',
                'created_by' => 1,
                'updated_by' => 1,
                'remember_token' => NULL,
                'deleted_at' => '2020-05-19 20:20:11',
                'created_at' => '2019-09-08 00:00:00',
                'updated_at' => '2020-05-07 16:18:42',
            ),
            1 => 
            array (
                'id' => 3,
                'branch_id' => 2,
                'username' => 'praveen',
                'permission_type' => 'R',
                'fname' => 'Praveen',
                'lname' => 'Chameera',
                'telephone' => NULL,
                'email' => 'praveenchameera@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$wrYDkBGInOMdmcYB08KFUu7gJFBpw7er7AgO.udz6cUsjE3TnRF/G',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2019-10-26 12:58:58',
                'updated_at' => '2019-10-26 12:58:58',
            ),
            2 => 
            array (
                'id' => 4,
                'branch_id' => NULL,
                'username' => 'tfsa',
                'permission_type' => 'R',
                'fname' => 'fdsa',
                'lname' => 'frwe',
                'telephone' => NULL,
                'email' => 'fdsa@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$N1H4KyueDlmSzF28ig6shug/s.OYfZKbQF0fWQ1PwWH/G0f9yLqgm',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 3,
                'updated_by' => 3,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2019-10-26 19:14:19',
                'updated_at' => '2019-10-26 19:14:19',
            ),
            3 => 
            array (
                'id' => 5,
                'branch_id' => 1,
                'username' => 'pramix',
                'permission_type' => 'R',
                'fname' => 'Praveen',
                'lname' => 'Chameera',
                'telephone' => NULL,
                'email' => 'praveenchameera@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$bbARN7XrptX8/rC19BK2w.lORr7/hHWV8wQEy5UoEr0HpYRhv/e/i',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 1,
                'updated_by' => 5,
                'remember_token' => 'vhnJm9XyIiJx10c5qPCyHZ0mU65hwl1zcrlBmZlg6XhdAxVaT2D1wM2k1oTD',
                'deleted_at' => NULL,
                'created_at' => '2020-01-29 10:53:08',
                'updated_at' => '2020-05-09 15:41:11',
            ),
            4 => 
            array (
                'id' => 6,
                'branch_id' => 2,
                'username' => 'xPraveeNx',
                'permission_type' => 'R',
                'fname' => 'Shamila',
                'lname' => 'Chathuranga',
                'telephone' => NULL,
                'email' => 'Praveen@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$zXyP/2pipDgO5Xnd1FXC7OJsucEM6dOLDsl6VtnhzO50dMB3N1T1e',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-01-29 11:21:06',
                'updated_at' => '2020-01-29 11:23:27',
            ),
            5 => 
            array (
                'id' => 7,
                'branch_id' => 1,
                'username' => 'Malaka',
                'permission_type' => 'R',
                'fname' => 'Malaka',
                'lname' => '',
                'telephone' => NULL,
                'email' => 'hasinidumalaka@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$2MdJIGSxO88Ny/8xgDbVPOs84jPq1ZCIedCMHa/FGGxVNvj/zCDJq',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 5,
                'updated_by' => 7,
                'remember_token' => '8DcHT78ed405xotsEBtJMOzvZkXpNqTIofQeZwK6re67om9xbETzgEMkaQqt',
                'deleted_at' => NULL,
                'created_at' => '2020-05-10 09:02:25',
                'updated_at' => '2020-05-20 07:27:39',
            ),
            6 => 
            array (
                'id' => 8,
                'branch_id' => 1,
                'username' => 'Lakshika',
                'permission_type' => 'R',
                'fname' => 'Lakshika',
                'lname' => '',
                'telephone' => NULL,
                'email' => 'slakshi99@gmail.com',
                'theme' => 'L',
                'password' => '$2y$10$KRvOJOQefg7w1n.94LkgK..5ikHP2Jot4ACyOREe3bUoea6aovxCC',
                'status' => '1',
                'email_verified_at' => NULL,
                'created_by' => 7,
                'updated_by' => 5,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-05-20 07:42:52',
                'updated_at' => '2020-08-07 15:47:50',
            ),
        ));
        
        
    }
}