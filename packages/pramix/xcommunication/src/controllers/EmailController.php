<?php

namespace Pramix\XCommunication\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Pramix\XCustomer\Models\CustomerModel;
use \Pramix\XCommunication\Models\SendSMSModel;
use Datatables;
use \Pramix\XCommunication\Models\EmailModel;
use Pramix\XOptions\Models\MediaModel;
use Config;
use \Pramix\XCommunication\Models\EmailTemplatesModel;
use Pramix\XCustomer\Models\CustomerAddressesModel;

class EmailController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("xcommunication::email_list");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $shortcodes = array_keys(config('xcommunication.shortcodes'));
        $shortcodes = implode(" , ", $shortcodes);
        $customers = CustomerModel::where('email', '<>', '')->get()->pluck('full_name', 'id');
        return view("xcommunication::sendemail.create_email")->with('shortcodes', $shortcodes)->with('customers', $customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        parse_str($request['campain_details'], $email_details);


        $send_time = $request['sendTime'];
        $template = new EmailTemplatesModel();
        $template->content = $request['message'];
        $template->editor_content = $request['editor_content'];
        $template->save();

        /*
          if ($email_details['email_type'] == 'customers_list') {
          EmailModel::SendEmailToSelectedCustomers($email_details, $send_time, $template->id);
          } else if ($email_details['email_type'] == 'all_customers') {
          EmailModel::SendEmailToAllCustomers($send_time, $template->id);
          } else if ($email_details['email_type'] == 'other_emails') {
          EmailModel::SendEmailToOtherEmail($email_details, $send_time, $template->id);
          } else if ($email_details['email_type'] == 'by_industry') {
          $industry = $request['industry'];
          if (!empty($industry)) {
          $customers = CustomerModel::where('industry', $industry)->pluck('id');
          EmailModel::SendEmailToSelectedCustomers($customers, $send_time, $template->id);
          }
          } */

        if ($email_details['email_type'] == 'other_emails') {
            EmailModel::SendEmailToOtherEmail($email_details, $send_time, $template->id);
        } else {
            $data_table = $request['data_table_content'];

            $customer_ids = $data_table;

            EmailModel::SendEmailToSelectedCustomers($customer_ids, $send_time, $template->id);
        }

        return '{"status": "success", "msg": "Emails Sent"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function listAllSendEmails() {
        $emaillist = EmailModel::select('id', 'recipient_email', 'customer_id', 'send_time', 'message', 'status', 'created_by')
                ->with(array('customer' => function($query) {
                        $query->select('id', 'prefix', 'fname', 'lname');
                    }))
                ->with(array('send_by' => function($query) {
                        $query->select('id', 'username');
                    }))
                ->orderBy('created_at', 'desc')
                ->get();



        return Datatables::of($emaillist)
                        ->addColumn('send_by', function ($emaillist) {

                            return $emaillist->send_by['username'];
                        })
                        ->editColumn('status', function ($emaillist) {

                            if ($emaillist->status == 0) {
                                return '<span class="text-danger">' . __('xcommunication::sendsms.labels.pending') . '</span>';
                            }
                            if ($emaillist->status == 1) {
                                return '<span class="text-success">' . __('xcommunication::sendsms.labels.completed') . '</span>';
                            }
                        })
                        ->addColumn('customer', function ($emaillist) {
                            return $emaillist->customer['prefix'] . '.' . $emaillist->customer['fname'] . ' ' . $emaillist->customer['lname'];
                        })
                        ->addColumn('actions', function ($emaillist) {
                            $buttons = '<button id="view_email" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="View" aria-describedby="tooltip934027"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                            return $buttons;
                        })
                        ->rawColumns(['status', 'actions'])
                        ->make(true);
    }

    public function getEmailEditor($template_id = NULL) {
        $editor_content = '';

        if ($template_id != NULL) {
            $template = TemplateModel::find($template_id);
            $editor_content = $template->editor_content;
        }
        return view("xcommunication::email_editor.email_editor")->with('editor_content', $editor_content);
    }

    public function uploadMedia(Request $request) {

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $media = MediaModel::uploadMedia($file, Config::get('xcommunication.media_array.email_uploads.type'), Config::get('xcommunication.media_array.email_uploads.folder_name'));


            return $media;
        }
    }

    public function getMedia() {

        $media = MediaModel::where('media_type', Config::get('xcommunication.media_array.email_uploads.type'))->pluck('file_name')->toArray();

        //creating response
        $response = array();

        $response['code'] = 0;
        $response['files'] = $media;
        $response['directory'] = url('uploads/' . Config::get('xcommunication.media_array.email_uploads.folder_name')) . '/';


        return json_encode($response);
    }

    public function previewSentEmail($email_id) {
        $email = EmailModel::find($email_id);
        $template = EmailTemplatesModel::find($email->template_id);
        if ($template != NULL)
            return $template->content;
    }

    public function getCustomersByRadioType(Request $request) {

        $fitering_method = $request['customers_filtering_method'];

        if ($fitering_method == 'customer_list') {

            $customer_id = $request['customers_list'];
            $customers = CustomerModel::where('id', $customer_id)->get();
        } else if ($fitering_method == 'all') {

            $customers = CustomerModel::where('email', '<>', '')->get();
        } else if ($fitering_method == 'industry') {

            $industry_id = $request['industry_id'];
            $customers = CustomerModel::where('industry', $industry_id)->where('email', '<>', '')->get();
        }


        return Datatables::of($customers)
                        ->addColumn('fullName', function ($customers) {

                            return $customers->prefix . '.' . $customers->fname . ' ' . $customers->lname;
                        })
                        ->editColumn('industry', function ($customers) {
                            if ($customers->industry != NULL) {
                                return config('xcustomer.industries.' . $customers->industry);
                            } else {
                                return '';
                            }
                        })
                        ->addColumn('action', function ($customers) {
                            $buttons = '<button id="delete_customer" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                            return $buttons;
                        })
                        ->make(true);

        // return response()->json(['status' => 'success', 'customers' => $customers]);
    }

}
