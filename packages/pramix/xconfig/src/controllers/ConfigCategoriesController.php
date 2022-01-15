<?php

namespace Pramix\XConfig\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XConfig\Models\ConfigCategoryModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;



class ConfigCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('xconfig::config_category_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('xconfig::create_config_category');

    }

    public function getConfigCategoriesList()
    {
        $categories = ConfigCategoryModel::all();

        return Datatables::of($categories)

            ->editColumn('status', function ($categories) {
                if($categories->status == 1)
                    return '<p class="text-success"><strong>Active</strong></p>';
                else
                    return '<p class="text-danger"><strong>Disable</strong></p>';
            })

            ->addColumn('action', function ($categories) {
                return '<a class = "btn btn-info btn-xs" href="' . url("/config_categories/" . $categories->id . "/edit") . '" id="edit_config" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        parse_str($request['config_category_details'], $config_category_details);


        $validator = Validator::make($config_category_details, [
            'config_category_name' => 'required|unique:configuration_category,name',



        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }




        $config_cat = new ConfigCategoryModel();
        $config_cat->name = $config_category_details['config_category_name'];
        $config_cat->description = $config_category_details['description'];
        $config_cat->status = $config_category_details['status_radio'];
        $config_cat->save();

        return response()->json(['status' => 'success', 'msg' => 'Success', 'category_id' => $config_cat->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = ConfigCategoryModel::find($id);
        return view('xconfig::create_config_category')->with('config_category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        parse_str($request['config_category_details'], $config_category_details);


        $validator = Validator::make($config_category_details, [
            'config_category_name' => 'required|unique:configuration_category,name,'.$id,
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }




        $config_cat = ConfigCategoryModel::find($id);
        $config_cat->name = $config_category_details['config_category_name'];
        $config_cat->description = $config_category_details['description'];
        $config_cat->status = $config_category_details['status_radio'];
        $config_cat->save();

        return response()->json(['status' => 'success', 'msg' => 'Success', 'category_id' => $config_cat->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
