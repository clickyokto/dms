<?php

namespace Pramix\XConfig\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XConfig\Models\XConfig;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class XConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('xconfig::config_list');

    }


    public function getConfigsList()
    {
        $configs = XConfig::with('category')->get();

        return Datatables::of($configs)
            ->addColumn('category', function ($config) {
                return $config->category->name ?? '';
            })

            ->editColumn('status', function ($config) {
                if($config->status == 1)
                    return '<p class="text-success"><strong>Active</strong></p>';
                else
                    return '<p class="text-danger"><strong>Disable</strong></p>';
            })
            ->editColumn('config_type', function ($config) {
                if($config->config_type == 'TX')
                    return 'Text';
                else if($config->config_type == 'DD')
                    return 'Dropdown';
            })
            ->addColumn('action', function ($config) {
                return '<a class = "btn btn-info btn-xs" href="' . url("/configurations/" . $config->id . "/edit") . '" id="edit_config" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('xconfig::create_config');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parse_str($request['config_details'], $config_details);
        $validator = Validator::make($config_details, [
            'config_name' => 'required|unique:configurations,name',
            'display_name' => 'required|unique:configurations,display_name',
            'config_types' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }


        $select_type_options_array = array_combine($config_details['dropdown_value'], $config_details['dropdown_text']);


        $config = new XConfig();
        $config->name = $config_details['config_name'];
        $config->display_name = $config_details['display_name'];
        $config->config_type = $config_details['config_types'];

        if ($config_details['config_types'] == 'DD'){
            $config->value = $config_details['default_value'];
            $config->options_array = json_encode($select_type_options_array);
        }
        elseif ($config_details['config_types'] == 'TX')
            $config->value = $config_details['text_value'];


        $config->category_id = $config_details['config_category'];
        $config->status = $config_details['status_radio'];
        $config->save();

        return response()->json(['status' => 'success', 'msg' => 'Success']);


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $config = XConfig::find($id);
        return view('xconfig::create_config')->with('config',$config);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        parse_str($request['config_details'], $config_details);


        $select_type_options_array = array_combine($config_details['dropdown_value'], $config_details['dropdown_text']);


        $validator = Validator::make($config_details, [
            'config_name' => 'required|unique:configurations,name,'.$id,
            'display_name' => 'required|unique:configurations,display_name,'.$id,
            'config_types' => 'required',
        ]);


        $config =XConfig::find($id);
        $config->name = $config_details['config_name'];
        $config->display_name = $config_details['display_name'];
        $config->config_type = $config_details['config_types'];

        if ($config_details['config_types'] == 'DD'){
            $config->value = $config_details['default_value'];
            $config->options_array = json_encode($select_type_options_array);
        }
        elseif ($config_details['config_types'] == 'TX')
            $config->value = $config_details['text_value'];


        $config->category_id = $config_details['config_category'];
        $config->status = $config_details['status_radio'];
        $config->save();

        return response()->json(['status' => 'success', 'msg' => 'Success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
