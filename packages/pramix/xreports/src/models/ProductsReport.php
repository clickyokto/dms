<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XProduct\Models\ProductsModel;
use PDF;
use Carbon\Carbon;

class ProductsReport extends Model
{
    public static function generateProductsReport($filter_details = NULL)
    {

        $category_id = NULL;
        $product_type = NULL;




        if (isset($filter_details['product_catagory']) && $filter_details['product_catagory'] != '') {
            $category_id = $filter_details['product_catagory'];
        }
        if (isset($filter_details['product_type']) && $filter_details['product_type'] != '') {
            $product_type = $filter_details['product_type'];
        }

        $products = ProductsModel::with('category');
        if ($category_id != NULL) {
            $products->where('category_id', $category_id);
        }
        if ($product_type != NULL) {
            $products->where('type', $product_type);
        }

        $products=  $products->get();


        $category_details = NULL;


        if($category_id !=NULL)
        {
            $category_details = ProductCategoriesModel::find($category_id);
        }

        $pdf = PDF::loadView('xreports::inventory.products_report.products_report', array('products' => $products,'category_details' => $category_details,'product_type'=>$product_type));

        $path = 'reports/reports/inventory';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/products_report' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }

}
