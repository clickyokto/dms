<?php

namespace Pramix\XGeneral\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XGeneral\Models\AreaModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use App\Rules\BranchUniqueValidator;
use Illuminate\Support\Facades\Validator;
use Auth;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_AREAS');

        return view('xgeneral::areas.index');
    }

    public function getAreasList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_AREAS');

        $areas = AreaModel::get();

        $edit_area_permission = false;
         if (Auth::user()->can('EDIT_AREA')) {
        $edit_area_permission = true;
         }

        $delete_area_permission = false;

        if (Auth::user()->can('DELETE_AREA')) {
            $delete_area_permission = true;
        }
        return Datatables::of($areas)
            ->addColumn('action', function ($area) use ($edit_area_permission, $delete_area_permission) {
                $actions = '';
                if ($delete_area_permission) {
                    $actions .= '&nbsp;<button  class="delete_area btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                }

                if ($edit_area_permission) {
                    $actions .=  ' <a class = "btn btn-info btn-xs" href="' . url("/areas/" . $area->id . "/edit") . '" id="edit_$product" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }

                return $actions;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_AREA');

        return view('xgeneral::areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_AREA');

        parse_str($request['area_details'], $area_details);
        $validator = Validator::make($area_details, [
            'code' => ['max:10', 'unique:areas,code'],
            'area_name' => ['required', 'unique:areas,name', 'min:4'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $area_code = $area_details['code'];

        if ($area_code == '')
            $area_code = AreaModel::generateAreaCode($area_details['area_name']);

        $area = new AreaModel();
        $area->code = strtoupper($area_code);
        $area->name = $area_details['area_name'];
        $area->rep_id = $area_details['rep'] ?? 0;
        $area->save();
        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'area_details' => $area, 'id' => $area->id]);


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
    public function edit(Request $request, $id)
    {
        Permission::checkPermission($request, 'EDIT_AREA');

        $area = AreaModel::find($id);
        return view('xgeneral::areas.create')->with('area', $area);
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
        Permission::checkPermission($request, 'EDIT_AREA');

        parse_str($request['area_details'], $area_details);

        $validator = Validator::make($area_details, [
            'code' => ['required', 'max:10', 'unique:areas,code,' . $id],
            'area_name' => ['required', 'unique:areas,name,' . $id],
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $area = AreaModel::find($area_details['area_id']);
        $area->code = strtoupper($area_details['code']);
        $area->name = $area_details['area_name'];
        $area->rep_id = $area_details['rep'] ?? 0;
        $area->save();
        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'),'area_details' => $area, 'id' => $area->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        Permission::checkPermission($request, 'DELETE_CUSTOMER');

        $area = AreaModel::find($id);
        if ($area->delete())
            return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
        else
            return response()->json(['status' => 'error', 'msg' => __('common.errors.can_not_delete_record_used_somewhere')]);

    }
}
