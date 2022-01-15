<?php

namespace Pramix\XReports\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XInventory\Models\Inventory;
use PDF;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;

class InventoryMovementSummaryReport extends Model
{
    public static function generateInventoryMovementSummaryReport($filter_details = NULL)
    {
        $date_range = NULL;
        $from_date = NULL;
        $end_date = NULL;
        $product_id = NULL;
        $category_id = NULL;

        if ($filter_details['date_range'] != '') {
            $date_range_details = ReportsModel::getReportTimeRange($filter_details);
            $date_range = $filter_details['date_range'];
            $from_date = $date_range_details['from_date'];
            $end_date = $date_range_details['end_date'];
        }

        if (isset($filter_details['products']) && $filter_details['products'] != '') {
            $product_id = $filter_details['products'];
        }
        if (isset($filter_details['product_catagory']) && $filter_details['product_catagory'] != '') {
            $category_id = $filter_details['product_catagory'];
        }

        $inventory = Inventory::with('product')->with('user')->with('store');
        if ($product_id != NULL) {
            $inventory->where('product_id', $product_id);
        }
        if ($category_id != NULL) {
            $inventory->whereHas('product', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }
        if ($date_range != null) {
            $inventory->whereDate('created_at', '>=', $from_date);
        }
        if ($date_range != null) {
            $inventory->whereDate('created_at', '<=', $end_date);
        }
        $inventory = $inventory->get();

        $product_details = NULL;
        $category_details = NULL;
        if ($product_id != NULL) {
            $product_details = ProductsModel::find($product_id);
        }

        if ($category_id != NULL) {
            $category_details = ProductCategoriesModel::find($category_id);
        }

        $pdf = PDF::loadView('xreports::inventory.inventory_movement_summary.inventory_movement_summary', array('inventory_records' => $inventory, 'from_date' => $from_date, 'end_date' => $end_date, 'product_details' => $product_details, 'category_details' => $category_details));

        $path = 'reports/reports/inventory';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/inventory_movement_summary_report_' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }
}
