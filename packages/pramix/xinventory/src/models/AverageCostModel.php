<?php

namespace Pramix\XInventory\Models;


use Illuminate\Database\Eloquent\Model;
use Pramix\XProduct\Models\ProductsModel;

class AverageCostModel extends Model
{

    protected $table = 'average_cost_table';
    protected $primaryKey = 'id';

    public static function increaseQtyCostUpdate($product_id, $qty, $unit_price)
    {

        $last_record = AverageCostModel::where('product_id', $product_id)->orderBy('created_at', 'desc')->first();
        if ($last_record == NULL) {
            $unit = new AverageCostModel();
            $unit->product_id = $product_id;
            $unit->ending_units = $qty;
            $unit->average_cost_per_unit = $unit_price;
            $unit->total_cost = $qty * $unit_price;
            $unit->save();
        } else {

            $ending_units = $last_record->ending_units + $qty;
            $total_cost = ($qty * $unit_price) + $last_record->total_cost;

            $unit = new AverageCostModel();
            $unit->product_id = $product_id;
            $unit->ending_units = $ending_units;
            $unit->average_cost_per_unit = $total_cost / $ending_units;
            $unit->total_cost = $total_cost;
            $unit->save();
        }
        return TRUE;
    }

    public static function decreaseQtyCostUpdate($product_id, $qty, $unit_price = NULL)
    {
        //average cost
        //dd('de');
        $last_record = AverageCostModel::where('product_id', $product_id)->orderBy('created_at', 'desc')->first();
        if ($last_record != NULL) {

            $ending_units = $last_record->ending_units - $qty;

            if ($unit_price == NULL)
                $total_cost = $ending_units * $last_record->average_cost_per_unit;
            else
                $total_cost = $ending_units * $unit_price;

            if($ending_units == 0)
                $average_cost_per_unit = 0;
            else
                $average_cost_per_unit = $total_cost / $ending_units;

            $unit = new AverageCostModel();
            $unit->product_id = $product_id;
            $unit->ending_units = $ending_units;
            $unit->average_cost_per_unit = $average_cost_per_unit;
            $unit->total_cost = $total_cost;

            $unit->save();
        }
        return TRUE;
    }





    public static function getCost($product_id)
    {
        $product = ProductsModel::find($product_id);

        if ($product->type != 'stock') {
            return $product->cost;

        } else {
            $last_record = AverageCostModel::where('product_id', $product_id)->orderBy('created_at', 'desc')->first();

            if ($last_record != NULL) {
                return $last_record->average_cost_per_unit;
            } else {
                return 0;
            }
        }
    }

}