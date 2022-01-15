<?php

namespace Pramix\XCart\Models;

use Illuminate\Database\Eloquent\Model;
use Cart;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XProjects\Models\ProjectsModel;

class CartModel extends Model
{

    public static function addToCart($product_id, $qty = 1)
    {

        $product = ProductsModel::find($product_id);


            Cart::add(array(
                array(
                    'id' => $product->id,
                    'name' => $product->item_code,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'attributes' => array()
                )
            ));

        return true;
    }

}
