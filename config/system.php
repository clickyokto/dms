<?php

return[
    'default_country' => 'LK',
    'default_currency' => 'lkr',
    'app_default_logo_url' => Config('app.url') . '/public/uploads/logo.png',
    'product_picture_upload_path' => Config('app.url') . '/public/uploads/products',
    'product_types' => array(
        'stock' => 'Stocked Product',
        'non_stock' => 'Non-Stocked Product',
        'service' => 'Service',
    ),
    'default_product_type' => 'stock',
    'default_product_category' => 1,

    'default_country_code' => 'LK',
    'option_names' => array(
        'company_name' => 'company_name',
        'mobile' => 'mobile',
        'telephone' => 'telephone',
        'email' => 'email',
        'website' => 'website',
        'street1' => 'street1',
        'street2' => 'street2',
        'city' => 'city',
        'country' => 'country',
        'logo_url' => 'logo_url',
        'costing_method' => 'costing_method'
    ),
    'transaction_types' => array(
        'stock_adjustment' => 1,
        'sales_order' => 2,
        'purchase_order' => 3,
        'sales_order_restock' => 4,
        'reverse_return' => 5
    ),
    'costing_methods' => array(
        ['method' => ['id' => 'average_cost', 'name' => 'Average Cost']],
        //['method' => ['id' => 'fifo', 'name' => 'FIFO']],
        //['method' => ['id' => 'lifo', 'name' => 'LIFO']],
        ['method' => ['id' => 'manual', 'name' => 'Manual']]
    ),
    'invoice_date' => array(
        ['date' => ['id' => '', 'name' => 'All']],
        ['date' => ['id' => 'today', 'name' => 'Totay']],
        ['date' => ['id' => 'this_month', 'name' => 'This Month']],
        ['date' => ['id' => 'this_quarter', 'name' => 'This Quarter']],
        ['date' => ['id' => 'this_year', 'name' => 'This Year']],
        ['date' => ['id' => 'last_7_days', 'name' => 'Last 7 Days']],
        ['date' => ['id' => 'last_30_days', 'name' => 'Last 30 Days']],
        ['date' => ['id' => 'custom_date_range', 'name' => 'Custom Date Range']]
    ),
    'payment_status' => array(
        ['payment' => ['id' => '', 'name' => 'All']],
        ['payment' => ['id' => '3', 'name' => 'Partial']],
        ['payment' => ['id' => '2', 'name' => 'Unpaid']],
        ['payment' => ['id' => '1', 'name' => 'Paid']],
    ),
    'software_type' => 'user_base', //user base, company base
    'user_roles' => array(
        'super_administrator' => 1,
        'owner' => 2,
        'manager' => 3,
        'salesperson' => 4,
    ),
    'barcode' => false,
    'raw_material' => true,
    'create_invoice_without_customer' => false,
    'customer_complain_status' => array(
        ['complain' => ['id' => '1', 'name' => 'Open']],
        ['complain' => ['id' => '2', 'name' => 'Progress']],
        ['complain' => ['id' => '0', 'name' => 'Close']],
    ),
    'customer_complain_priority' => array(
        ['priority' => ['id' => '1', 'name' => 'Low']],
        ['priority' => ['id' => '2', 'name' => 'Medium']],
        ['priority' => ['id' => '3', 'name' => 'High']],
    ),
    'customer_type' => array(
        'v' => 'VIP',
        'p' => 'Primiam',
        'n' => 'Normal',
    ),
    'gender' => array(
        'm' => 'Male',
        'f' => 'Female ',
        'other' => 'Other',
    ),

    'translate' => false,
];
?>
