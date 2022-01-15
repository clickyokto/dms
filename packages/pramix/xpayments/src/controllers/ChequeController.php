<?php

namespace Pramix\XPayment\Controllers;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pramix\Templates\Models\ChequePrinteModel;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;

class ChequeController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CHEQUE');
        return view('xpayment::cheque_list');
    }

    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_CHEQUE');

        $page = 'cheque';
        return view('xpayment::create_cheque')
            ->with('page', $page);
    }


    public function store(Request $request)
    {

        Permission::checkPermission($request, 'ADD_CHEQUE');
        parse_str($request['cheque_details'], $cheque_details);

        $validator = Validator::make($cheque_details, [
            'date' => 'required',
            'bank' => 'required',
            'cheque_no' => 'required',
            'payer' => 'required',
            'cheque_date' => 'required',
            'amount' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $cheque = ChequeModel::find($cheque_details['cheque_id']);
        if ($cheque == null)
            $cheque = new ChequeModel();

        $cheque->cheque_no = $cheque_details['cheque_no'];
        $cheque->payer = $cheque_details['payer'];
        $cheque->date = $cheque_details['date'] ?? null;
        if (isset($cheque_details['cash_cheque']) && $cheque_details['cash_cheque'] == '1')
        $cheque->cash_cheque = $cheque_details['cash_cheque'];
        else
            $cheque->cash_cheque = null;
        $cheque->cheque_date = $cheque_details['cheque_date'];
        $cheque->bank = $cheque_details['bank'];
        $cheque->amount = $cheque_details['amount'];
        $cheque->remarks = $cheque_details['remarks'];
        $cheque->status = 1;
        if (isset($cheque_details['crossed']))
        $cheque->crossed = $cheque_details['crossed'];
        else
            $cheque->crossed = 0;
        $cheque->save();

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully')]);
    }


    public function show($id)
    {
    }

    public function edit(Request $request, $id)
    {
 Permission::checkPermission($request, 'EDIT_CHEQUE');
        $cheque = ChequeModel::where('id', $id)->first();
        $page = 'cheque';

        return view('xpayment::create_cheque')
            ->with('cheque',$cheque)
            ->with('page',$page);
    }

    public function update(Request $request, $id)
    {
//
    }


    public function destroy($id)
    {
        //
    }


    public function getChequesList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CHEQUE');
            $cheques = ChequeModel::all();

            return Datatables::of($cheques)
                ->editColumn('action', function ($cheques) {
                        return '<a class = "btn btn-info btn-xs" href="' . url("/cheque/" . $cheques->id . "/edit") . '" id="edit_cheques" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                })
                ->addColumn('payer_name', function ($cheques) {
                        return $cheques->payer;
                })
                ->addColumn('cash_cheque', function ($cheques) {
                    if ($cheques->cash_cheque== '1')
                    return 'Cash Cheque';
                })
                ->addColumn('bank_name', function ($cheques) {
                    if (isset($cheques->bank) && $cheques->bank != null)
                        return getConfigArrayValueByKey('BANKS', $cheques->bank);
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }

    public function chequePrint(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CHEQUE');

        $cheque_id = $request['cheque_id'];
        $pdf = ChequePrinteModel::printCheque($cheque_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }



    public function getAutoLoadData(Request $request, $element_type = null)
    {

        $term = $request['term'];
//        $quary = "where('name', 'like', '%' . $term . '%')->get()";
                $data = ChequeModel::take(10);

        $data = $data->where('payer', 'like', '%' . $term . '%')->get()->unique('payer');
        return response()->json($data);
    }


}
