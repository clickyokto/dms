<?php

namespace Pramix\XCommunication\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Pramix\XCandidate\Models\CandidateModel;
use Pramix\XCommunication\Models\SMSAPIModel;
use Pramix\XCustomer\Models\CustomerModel;
use \Pramix\XCommunication\Models\SendSMSModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;


class SMSController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Permission::checkPermission($request, 'SMS_MANAGEMENT');


      //  SendSMSModel::sendSMS();
        return view("xcommunication::sms_list");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Permission::checkPermission($request, 'SEND_NEW_SMS');


        $send_type = $request['send_type'];
        $send_candidate = $request['candidate_id'];

        $no_of_sms = SMSAPIModel::smsGatewayGetCreditCount();

        $shortcodes = array_keys(config('xcommunication.shortcodes'));
        $shortcodes = implode(" , ", $shortcodes);
        //  $customers = CustomerModel::where('mobile', '<>', '')->get()->pluck('full_name', 'id');
        $candidates = CustomerModel::where('mobile', '<>', '')->pluck('business_name', 'id');
        return view("xcommunication::sendsms.create_sms")->with('no_of_sms', $no_of_sms)->with('send_type', $send_type)->with('send_candidate', $send_candidate)->with('shortcodes', $shortcodes)->with('candidates', $candidates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::checkPermission($request, 'SEND_NEW_SMS');

        parse_str($request['campain_details'], $sms_details);
        $send_time = $request['sendTime'];
        $send_customers = $request['sendCustomers'];


        if ($sms_details['send_time_type'] == 'send_later' && $sms_details['send_time'] == '')
            return '{"status": "error", "msg": "Please select send time"}';

        if ($sms_details['sms_type'] == 'send_to_other_number_type') {

            SendSMSModel::SendSMSToOtherNumber($sms_details, $send_time);
        } else {
            $data_table = $request['data_table_content'];

            SendSMSModel::SendSMSToSelectedCustomers($sms_details, $data_table, $send_time);
        }

        return '{"status": "success", "msg": "SMS Send"}';

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
        //
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
        //
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

    public function listAllSendSMS(Request $request)
    {
        Permission::checkPermission($request, 'SMS_MANAGEMENT');


        $smslist = SendSMSModel::select('id', 'recipient_phone_no', 'customer_id', 'send_time', 'message', 'status', 'created_by')
            ->with('customer')
            ->with(array('send_by' => function ($query) {
                $query->select('id', 'username');
            }))
            ->orderBy('created_at', 'desc')
            ->get();


        return Datatables::of($smslist)
            ->addColumn('send_by', function ($smslist) {
                if ($smslist->created_by == 0)
                    return 'JC Document System';
                else {
                    if (isset($smslist->send_by['username']))
                        return $smslist->send_by['username'];
                    else
                        return 'JC Document System';
                }
            })
            ->editColumn('status', function ($smslist) {

                if ($smslist->status == 0) {
                    return '<strong><span class="text-danger">' . __('xcommunication::sendsms.labels.pending') . '</span></strong>';
                }
                if ($smslist->status == 1) {
                    return '<strong></strtong><strong class="text-success">' . __('xcommunication::sendsms.labels.completed') . '</strong>';
                }
            })
            ->addColumn('customer', function ($smslist) {

                return $smslist->customer['business_name'] . ' - ' . $smslist->customer['fname'].' '.$smslist->customer['lname'];
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function getCustomerList(Request $request)
    {




            $customers = CustomerModel::where('mobile_no1', '<>', '')->get();



        return Datatables::of($customers)

            ->addColumn('action', function ($customers) {
                $buttons = '<button id="delete_customer" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                return $buttons;
            })
            ->rawColumns([ 'action'])
            ->make(true);

    }

}
