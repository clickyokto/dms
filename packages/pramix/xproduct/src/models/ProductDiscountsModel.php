<?php

namespace Pramix\XProduct\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductDiscountsModel extends Model
{
    protected $table = 'product_discounts';
    protected $primaryKey = 'id';

    public static function saveDiscount($product_id, $details) {

        $discount = ProductDiscountsModel::where('product_id',$product_id)->first();

        if ($discount == NULL)
            $discount = new ProductDiscountsModel;
        $discount->product_id = $product_id;
        $discount->amount = $details['discount_amount'];
        $discount->discount_type = $details['discount_type'];
        $discount->start_date = $details['discount_start_date'] . ' ' . $details['discount_start_time'];
        $discount->end_date = $details['discount_end_date'] . ' ' . $details['discount_end_time'];
        $discount->limit = !empty($details['discount_limit']) ? $details['discount_limit'] : 0;
        $discount->status = 'A';
        $discount->save();
    }
}
