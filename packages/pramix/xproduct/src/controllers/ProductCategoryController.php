<?php

namespace Pramix\XProduct\Controllers;

use App\Rules\BranchUniqueValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       Permission::checkPermission($request , 'MANAGE_PRODUCT_CATEGORIES');

        $categories = ProductCategoriesModel::where('parent_id', 0)->get();
        return view('xproduct::category')->with('categories', $categories);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategoriesModel::where('parent_id', 0)->get();
        return view('xproduct::create_category')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::checkPermission($request , 'MANAGE_PRODUCT_CATEGORIES');
        parse_str($request['category'], $category_details);

        $validator = Validator::make($category_details, [
            'category_name' => ['required'],
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $category = new ProductCategoriesModel();
        $category->category_code = $category_details['category_code'];
        $category->category_name = $category_details['category_name'];
        $category->parent_id = $category_details['parent_category'];
        $category->description = $category_details['category_description'];

        if ( $category->save()) {
            return response()->json(['status' => 'success','msg' => __('common.messages.save_successfully'), 'category' => $category , 'id' => $category->id]);

        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {

        $category = ProductCategoriesModel::find($id);
        $categories = ProductCategoriesModel::where('parent_id', 0)->get();
        return view('xproduct::create_category')->with('categories', $categories)->with('category',$category);
    }


    public function update(Request $request, $id)
    {
        Permission::checkPermission($request , 'MANAGE_PRODUCT_CATEGORIES');
        parse_str($request['category'], $category_details);

        $validator = Validator::make($category_details, [
            'category_name' => ['required'],
        ]);
        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $category = ProductCategoriesModel::find($category_details['category_id']);
        $category->category_code = $category_details['category_code'];
        $category->category_name = $category_details['category_name'];
        $category->parent_id = $category_details['parent_category'];
        $category->description = $category_details['category_description'];

        if ( $category->save()) {
            return response()->json(['status' => 'success','msg' => __('common.messages.save_successfully'), 'category' => $category , 'id' => $category->id]);

        }
    }


    public function destroy($id)
    {
        //
    }

    public function getProductsByCategory(Request $request) {

        $product = DB::table('product')
            ->where('category_id', $request['category_id'])
            ->get();
        $category= ProductCategoriesModel::find($request['category_id']);



        return Datatables::of($product)
            ->with([
                'category' => $category,
            ])
            ->make(true);
    }


}
