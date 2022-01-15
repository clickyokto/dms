<?php

namespace Pramix\XGeneral\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pramix\XFinance\Models\GeneralFinanceModel;

class CategoryController extends Controller
{
    public function getGeneralCategoryByFinanceCategory(Request $request)
    {
        $finance_category_id = ($request['finance_category']);
        if ($finance_category_id != null)
            $general_categories = GeneralFinanceModel::where('category', $finance_category_id)->where('finance_type', $request['finance_type'])->get();
        else
            $general_categories = GeneralFinanceModel::where('finance_type', $request['finance_type'])->get();
        return response()->json(['status' => 'success', 'general_categories' => $general_categories]);
    }


    public function getFinanceCategoryByGeneralCategory(Request $request)
    {
        $general_categories_id = ($request['general_category_id']);

        $general_category = GeneralFinanceModel::find($general_categories_id);

        if ($general_category != null)
            return response()->json(['status' => 'success', 'general_category' => $general_category]);
        else
            return response()->json(['status' => 'success', 'general_category' => '']);

    }
}
