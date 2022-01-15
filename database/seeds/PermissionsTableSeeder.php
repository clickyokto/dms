<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'MANAGE_CUSTOMERS',
                'description' => '',
                'display_name' => 'Manage Customers',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-10-26 11:32:36',
                'updated_at' => '2019-11-10 14:51:00',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'ADD_CUSTOMER',
                'description' => '',
                'display_name' => 'Add Customer',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => '2019-11-10 14:51:19',
                'updated_at' => '2019-11-10 14:51:19',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'EDIT_CUSTOMER',
                'description' => '',
                'display_name' => 'Edit Customer',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => '2019-11-10 14:51:29',
                'updated_at' => '2019-11-10 14:51:29',
            ),
            3 => 
            array (
                'id' => 7,
                'name' => 'MANAGE_PRODUCTS',
                'description' => '',
                'display_name' => 'Manage Products',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-10 14:52:20',
                'updated_at' => '2019-11-10 14:52:20',
            ),
            4 => 
            array (
                'id' => 8,
                'name' => 'ADD_PRODUCTS',
                'description' => '',
                'display_name' => 'Add Products',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2019-11-10 14:52:34',
                'updated_at' => '2019-11-10 14:52:34',
            ),
            5 => 
            array (
                'id' => 9,
                'name' => 'EDIT_PRODUCTS',
                'description' => '',
                'display_name' => 'Edit Products',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2019-11-10 14:53:21',
                'updated_at' => '2019-11-10 14:53:21',
            ),
            6 => 
            array (
                'id' => 101,
                'name' => 'ADD_BILL_PAYMENT',
                'description' => '',
                'display_name' => 'Add bill payment',
                'guard_name' => 'web',
                'parent_id' => 100,
                'created_at' => '2020-05-07 11:07:28',
                'updated_at' => '2020-05-07 11:07:28',
            ),
            7 => 
            array (
                'id' => 12,
                'name' => 'MANAGE_PRODUCT_CATEGORIES',
                'description' => '',
                'display_name' => 'Manage Product Categories',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2019-11-10 15:16:31',
                'updated_at' => '2019-11-10 15:16:31',
            ),
            8 => 
            array (
                'id' => 13,
                'name' => 'MANAGE_SUPPLIERS',
                'description' => '',
                'display_name' => 'Manage Suppliers',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 01:52:55',
                'updated_at' => '2019-11-13 01:52:55',
            ),
            9 => 
            array (
                'id' => 14,
                'name' => 'ADD_SUPPLIER',
                'description' => '',
                'display_name' => 'Add Supplier',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => '2019-11-13 01:53:25',
                'updated_at' => '2019-11-13 01:53:25',
            ),
            10 => 
            array (
                'id' => 15,
                'name' => 'EDIT_SUPPLIER',
                'description' => '',
                'display_name' => 'Edit Supplier',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => '2019-11-13 01:53:59',
                'updated_at' => '2019-11-13 01:53:59',
            ),
            11 => 
            array (
                'id' => 100,
                'name' => 'MANAGE_BILL_PAYMENT',
                'description' => '',
                'display_name' => 'Manage bill payment',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-05-07 11:03:12',
                'updated_at' => '2020-05-07 11:03:12',
            ),
            12 => 
            array (
                'id' => 20,
                'name' => 'MANAGE_GRN',
                'description' => '',
                'display_name' => 'Manage GRN',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 08:15:36',
                'updated_at' => '2019-11-13 08:15:36',
            ),
            13 => 
            array (
                'id' => 21,
                'name' => 'ADD_GRN',
                'description' => '',
                'display_name' => 'Add GRN',
                'guard_name' => 'web',
                'parent_id' => 20,
                'created_at' => '2019-11-13 08:15:56',
                'updated_at' => '2019-11-13 08:16:32',
            ),
            14 => 
            array (
                'id' => 22,
                'name' => 'EDIT_GRN',
                'description' => '',
                'display_name' => 'Edit GRN',
                'guard_name' => 'web',
                'parent_id' => 20,
                'created_at' => '2019-11-13 08:16:19',
                'updated_at' => '2019-11-13 08:16:19',
            ),
            15 => 
            array (
                'id' => 23,
                'name' => 'APPROVE_GRN',
                'description' => '',
                'display_name' => 'Approve GRN',
                'guard_name' => 'web',
                'parent_id' => 20,
                'created_at' => '2019-11-13 08:21:50',
                'updated_at' => '2019-11-13 08:21:50',
            ),
            16 => 
            array (
                'id' => 25,
                'name' => 'MANAGE_GRN_RETURN',
                'description' => '',
                'display_name' => 'Manage GRN Return',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 11:50:11',
                'updated_at' => '2019-11-13 11:50:11',
            ),
            17 => 
            array (
                'id' => 26,
                'name' => 'ADD_GRN_RETURN',
                'description' => '',
                'display_name' => 'Add GRN Return',
                'guard_name' => 'web',
                'parent_id' => 25,
                'created_at' => '2019-11-13 11:50:38',
                'updated_at' => '2019-11-13 11:50:38',
            ),
            18 => 
            array (
                'id' => 27,
                'name' => 'EDIT_GRN_RETURN',
                'description' => '',
                'display_name' => 'Edit GRN Return',
                'guard_name' => 'web',
                'parent_id' => 25,
                'created_at' => '2019-11-13 11:51:07',
                'updated_at' => '2019-11-13 11:51:57',
            ),
            19 => 
            array (
                'id' => 33,
                'name' => 'MANAGE_INVOICE',
                'description' => '',
                'display_name' => 'Manage invoice',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 13:12:26',
                'updated_at' => '2019-11-13 13:12:26',
            ),
            20 => 
            array (
                'id' => 34,
                'name' => 'ADD_INVOICE',
                'description' => '',
                'display_name' => 'Add invoice',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2019-11-13 13:12:56',
                'updated_at' => '2019-11-13 13:12:56',
            ),
            21 => 
            array (
                'id' => 35,
                'name' => 'EDIT_INVOICE',
                'description' => '',
                'display_name' => 'Edit invoice',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2019-11-13 13:13:09',
                'updated_at' => '2019-11-13 13:14:20',
            ),
            22 => 
            array (
                'id' => 37,
                'name' => 'MANAGE_CREDIT_NOTE',
                'description' => '',
                'display_name' => 'Manage credit note',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 13:14:44',
                'updated_at' => '2020-12-21 12:26:42',
            ),
            23 => 
            array (
                'id' => 38,
                'name' => 'ADD_CREDIT_NOTE',
                'description' => '',
                'display_name' => 'Add credit note',
                'guard_name' => 'web',
                'parent_id' => 37,
                'created_at' => '2019-11-13 13:15:07',
                'updated_at' => '2020-12-21 12:27:10',
            ),
            24 => 
            array (
                'id' => 39,
                'name' => 'EDIT_CREDIT_NOTE',
                'description' => '',
                'display_name' => 'Edit credit note',
                'guard_name' => 'web',
                'parent_id' => 37,
                'created_at' => '2019-11-13 13:15:28',
                'updated_at' => '2020-12-21 12:27:27',
            ),
            25 => 
            array (
                'id' => 45,
                'name' => 'MANAGE_CONFIGURATION',
                'description' => '',
                'display_name' => 'Manage configuration',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => '2019-11-13 13:27:17',
                'updated_at' => '2019-11-13 13:30:08',
            ),
            26 => 
            array (
                'id' => 46,
                'name' => 'MANAGE_SETTING',
                'description' => '',
                'display_name' => 'Manage setting',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 13:27:50',
                'updated_at' => '2019-11-13 13:27:50',
            ),
            27 => 
            array (
                'id' => 47,
                'name' => 'MANAGE_USERS',
                'description' => '',
                'display_name' => 'Manage users',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => '2019-11-13 13:28:10',
                'updated_at' => '2019-11-13 13:29:37',
            ),
            28 => 
            array (
                'id' => 48,
                'name' => 'MANAGE_ROLES',
                'description' => '',
                'display_name' => 'Manage roles',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => '2019-11-13 13:28:28',
                'updated_at' => '2019-11-13 13:29:51',
            ),
            29 => 
            array (
                'id' => 49,
                'name' => 'MANAGE_PERMISSIONS',
                'description' => '',
                'display_name' => 'Manage permissions',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => '2019-11-13 13:29:21',
                'updated_at' => '2019-11-13 13:29:21',
            ),
            30 => 
            array (
                'id' => 50,
                'name' => 'MANAGE_COMPANY_INFORMATION',
                'description' => '',
                'display_name' => 'Manage company information',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => '2019-11-13 13:32:01',
                'updated_at' => '2019-11-13 13:32:01',
            ),
            31 => 
            array (
                'id' => 51,
                'name' => 'MANAGE_INCOMES',
                'description' => '',
                'display_name' => 'Manage incomes',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 13:32:48',
                'updated_at' => '2019-11-13 13:32:48',
            ),
            32 => 
            array (
                'id' => 52,
                'name' => 'ADD_INCOMES',
                'description' => '',
                'display_name' => 'Add incomes',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => '2019-11-13 13:33:10',
                'updated_at' => '2019-11-13 13:33:10',
            ),
            33 => 
            array (
                'id' => 53,
                'name' => 'EDIT_INCOMES',
                'description' => '',
                'display_name' => 'Edit incomes',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => '2019-11-13 13:33:23',
                'updated_at' => '2019-11-13 13:33:23',
            ),
            34 => 
            array (
                'id' => 55,
                'name' => 'MANAGE_INCOME_CATEGORIES',
                'description' => '',
                'display_name' => 'Manage income categories',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => '2019-11-13 13:34:35',
                'updated_at' => '2019-11-13 13:34:35',
            ),
            35 => 
            array (
                'id' => 56,
                'name' => 'MANAGE_EXPENSES',
                'description' => '',
                'display_name' => 'Manage expenses',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 13:34:54',
                'updated_at' => '2019-11-13 13:34:54',
            ),
            36 => 
            array (
                'id' => 57,
                'name' => 'ADD_EXPENSES',
                'description' => '',
                'display_name' => 'Add expenses',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => '2019-11-13 13:35:06',
                'updated_at' => '2019-11-13 13:35:06',
            ),
            37 => 
            array (
                'id' => 58,
                'name' => 'EDIT_EXPENSES',
                'description' => '',
                'display_name' => 'Edit expenses',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => '2019-11-13 13:35:18',
                'updated_at' => '2019-11-13 13:35:18',
            ),
            38 => 
            array (
                'id' => 60,
                'name' => 'MANAGE_EXPENSE_CATEGORIES',
                'description' => '',
                'display_name' => 'Manage expense categories',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => '2019-11-13 13:36:11',
                'updated_at' => '2019-11-13 13:36:11',
            ),
            39 => 
            array (
                'id' => 61,
                'name' => 'ADD_PERMISSION',
                'description' => '',
                'display_name' => 'Add permission',
                'guard_name' => 'web',
                'parent_id' => 49,
                'created_at' => '2019-11-13 16:45:57',
                'updated_at' => '2019-11-13 16:45:57',
            ),
            40 => 
            array (
                'id' => 62,
                'name' => 'EDIT_PERMISSION',
                'description' => '',
                'display_name' => 'Edit permission',
                'guard_name' => 'web',
                'parent_id' => 49,
                'created_at' => '2019-11-13 16:46:18',
                'updated_at' => '2019-11-13 16:46:18',
            ),
            41 => 
            array (
                'id' => 64,
                'name' => 'ADD_ROLES',
                'description' => '',
                'display_name' => 'Add roles',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2019-11-13 16:47:20',
                'updated_at' => '2019-11-13 16:47:20',
            ),
            42 => 
            array (
                'id' => 65,
                'name' => 'EDIT_ROLES',
                'description' => '',
                'display_name' => 'Edit roles',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2019-11-13 16:47:27',
                'updated_at' => '2019-11-13 16:47:56',
            ),
            43 => 
            array (
                'id' => 67,
                'name' => 'ADD_USERS',
                'description' => '',
                'display_name' => 'Add users',
                'guard_name' => 'web',
                'parent_id' => 47,
                'created_at' => '2019-11-13 16:48:14',
                'updated_at' => '2019-11-13 16:48:14',
            ),
            44 => 
            array (
                'id' => 68,
                'name' => 'EDIT_USERS',
                'description' => '',
                'display_name' => 'Edit users',
                'guard_name' => 'web',
                'parent_id' => 47,
                'created_at' => '2019-11-13 16:48:32',
                'updated_at' => '2019-11-13 16:49:26',
            ),
            45 => 
            array (
                'id' => 70,
                'name' => 'MANAGE_REPORTS',
                'description' => '',
                'display_name' => 'Manage reports',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-11-13 17:43:26',
                'updated_at' => '2019-11-13 17:43:26',
            ),
            46 => 
            array (
                'id' => 71,
                'name' => 'APPROVE_GRN_RETURN',
                'description' => '',
                'display_name' => 'Approve GRN Return',
                'guard_name' => 'web',
                'parent_id' => 25,
                'created_at' => '2019-11-14 21:20:49',
                'updated_at' => '2019-11-14 21:20:49',
            ),
            47 => 
            array (
                'id' => 79,
                'name' => 'MANAGE_PAYMENT',
                'description' => '',
                'display_name' => 'Manage payment',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2019-12-03 15:24:40',
                'updated_at' => '2019-12-03 15:24:40',
            ),
            48 => 
            array (
                'id' => 80,
                'name' => 'ADD_PAYMENT',
                'description' => '',
                'display_name' => 'Add payment',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => '2019-12-03 15:25:04',
                'updated_at' => '2019-12-03 15:25:04',
            ),
            49 => 
            array (
                'id' => 81,
                'name' => 'EDIT_PAYMENT',
                'description' => '',
                'display_name' => 'Edit payment',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => '2019-12-03 15:25:26',
                'updated_at' => '2019-12-03 15:25:26',
            ),
            50 => 
            array (
                'id' => 102,
                'name' => 'MANAGE_PURCHASE_ORDER',
                'description' => '',
                'display_name' => 'Manage purchase order',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-05-07 11:12:47',
                'updated_at' => '2020-05-07 11:12:47',
            ),
            51 => 
            array (
                'id' => 103,
                'name' => 'ADD_PURCHASE_ORDER',
                'description' => '',
                'display_name' => 'Add purchase order',
                'guard_name' => 'web',
                'parent_id' => 102,
                'created_at' => '2020-05-07 11:13:20',
                'updated_at' => '2020-05-07 11:13:20',
            ),
            52 => 
            array (
                'id' => 104,
                'name' => 'EDIT_PURCHASE_ORDER',
                'description' => '',
                'display_name' => 'Edit purchase order',
                'guard_name' => 'web',
                'parent_id' => 102,
                'created_at' => '2020-05-07 11:13:40',
                'updated_at' => '2020-05-07 11:13:40',
            ),
            53 => 
            array (
                'id' => 105,
                'name' => 'APPROVE_PURCHASE_ORDER',
                'description' => '',
                'display_name' => 'Approve purchase order',
                'guard_name' => 'web',
                'parent_id' => 102,
                'created_at' => '2020-05-07 11:15:15',
                'updated_at' => '2020-05-07 11:15:15',
            ),
            54 => 
            array (
                'id' => 106,
                'name' => 'MANAGE_PRODUCTION',
                'description' => '',
                'display_name' => 'Manage production',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-05-07 11:30:50',
                'updated_at' => '2020-05-07 11:30:50',
            ),
            55 => 
            array (
                'id' => 107,
                'name' => 'ADD_PRODUCTION',
                'description' => '',
                'display_name' => 'Add production',
                'guard_name' => 'web',
                'parent_id' => 106,
                'created_at' => '2020-05-07 11:31:12',
                'updated_at' => '2020-05-07 11:31:12',
            ),
            56 => 
            array (
                'id' => 108,
                'name' => 'EDIT_PRODUCTION',
                'description' => '',
                'display_name' => 'Edit production',
                'guard_name' => 'web',
                'parent_id' => 106,
                'created_at' => '2020-05-07 11:31:38',
                'updated_at' => '2020-05-07 11:31:38',
            ),
            57 => 
            array (
                'id' => 109,
                'name' => 'MANAGE_STORE_LOCATION',
                'description' => '',
                'display_name' => 'Manage store location',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-05-07 11:31:53',
                'updated_at' => '2020-05-07 11:31:53',
            ),
            58 => 
            array (
                'id' => 110,
                'name' => 'ADD_STORE_LOCATION',
                'description' => '',
                'display_name' => 'Add store location',
                'guard_name' => 'web',
                'parent_id' => 109,
                'created_at' => '2020-05-07 11:32:24',
                'updated_at' => '2020-05-07 11:32:24',
            ),
            59 => 
            array (
                'id' => 111,
                'name' => 'EDIT_STORE_LOCATION',
                'description' => '',
                'display_name' => 'Edit store location',
                'guard_name' => 'web',
                'parent_id' => 109,
                'created_at' => '2020-05-07 11:32:40',
                'updated_at' => '2020-05-07 11:32:40',
            ),
            60 => 
            array (
                'id' => 112,
                'name' => 'APPROVE_INVOICE',
                'description' => '',
                'display_name' => 'Approve invoice',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2020-05-07 12:41:40',
                'updated_at' => '2020-05-07 12:41:40',
            ),
            61 => 
            array (
                'id' => 113,
                'name' => 'INVENTORY_MANUAL_UPDATE',
                'description' => '',
                'display_name' => 'Inventory manual update',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-05-07 14:33:05',
                'updated_at' => '2020-05-07 14:33:05',
            ),
            62 => 
            array (
                'id' => 114,
                'name' => 'APPROVE_PRODUCTION',
                'description' => '',
                'display_name' => 'Approve production',
                'guard_name' => 'web',
                'parent_id' => 106,
                'created_at' => '2020-05-07 15:46:16',
                'updated_at' => '2020-05-07 15:46:16',
            ),
            63 => 
            array (
                'id' => 115,
                'name' => 'MANAGE_CHEQUE_PAYMENT',
                'description' => '',
                'display_name' => 'Manage cheque payment',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2020-08-29 05:28:48',
                'updated_at' => '2020-08-29 05:28:48',
            ),
            64 => 
            array (
                'id' => 116,
                'name' => 'DELETE_CUSTOMER',
                'description' => '',
                'display_name' => 'Delete customer',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => '2020-12-20 07:16:25',
                'updated_at' => '2020-12-20 07:16:25',
            ),
            65 => 
            array (
                'id' => 117,
                'name' => 'MANAGE_AREAS',
                'description' => '',
                'display_name' => 'Manage areas',
                'guard_name' => 'web',
                'parent_id' => 0,
                'created_at' => '2020-12-20 07:33:08',
                'updated_at' => '2020-12-20 07:33:08',
            ),
            66 => 
            array (
                'id' => 118,
                'name' => 'EDIT_AREA',
                'description' => '',
                'display_name' => 'Edit area',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => '2020-12-20 07:33:28',
                'updated_at' => '2020-12-20 07:45:08',
            ),
            67 => 
            array (
                'id' => 119,
                'name' => 'ADD_AREA',
                'description' => '',
                'display_name' => 'Add area',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => '2020-12-20 07:33:47',
                'updated_at' => '2020-12-20 07:33:47',
            ),
            68 => 
            array (
                'id' => 120,
                'name' => 'DELETE_AREA',
                'description' => '',
                'display_name' => 'Delete area',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => '2020-12-20 07:34:01',
                'updated_at' => '2020-12-20 07:34:01',
            ),
            69 => 
            array (
                'id' => 121,
                'name' => 'DELETE_GRN',
                'description' => '',
                'display_name' => 'Delete grn',
                'guard_name' => 'web',
                'parent_id' => 20,
                'created_at' => '2020-12-21 03:08:08',
                'updated_at' => '2020-12-21 03:08:08',
            ),
            70 => 
            array (
                'id' => 122,
                'name' => 'DELETE_INVOICE',
                'description' => '',
                'display_name' => 'Delete invoice',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2020-12-21 09:20:58',
                'updated_at' => '2020-12-21 09:20:58',
            ),
            71 => 
            array (
                'id' => 123,
                'name' => 'CHANGE_INVOICE_PRODUCT_PRICE',
                'description' => '',
                'display_name' => 'Change invoice product price',
                'guard_name' => 'web',
                'parent_id' => 33,
                'created_at' => '2020-12-21 16:11:24',
                'updated_at' => '2020-12-21 16:11:24',
            ),
        ));
        
        
    }
}