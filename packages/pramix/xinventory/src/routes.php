<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XInventory\Controllers'], function() {

          Route::put('change_store_qty/{inventory_id}', 'InventoryController@changeStoreQty');
        Route::get('get_inventory', 'InventoryController@getInventory');
        Route::resource('inventory', 'InventoryController');
    });


});
