<?php

namespace Pramix\XEmailSender\Controllers;

use App\Mail\InvoiceMail;
use PhpParser\Node\Expr\Array_;
use Pramix\XEmailSender\Mail\SendMailSupport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Pramix\Templates\Models\GenerateInvoiceModel;
use Pramix\Templates\Models\GenerateQuotationModel;
use Pramix\XEmailSender\Models\EmailSenderModel;
use Pramix\XEmailSender\Models\XEmailSender;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class EmailSenderController extends Controller
{
    public function index()
    {
        return view('xemail_sender::email_list');
    }


    public function getEmailsList()
    {
        $emails = EmailSenderModel::get();

        return Datatables::of($emails)
            ->editColumn('status', function ($emails) {
                if ($emails->status == 'P')
                    return '<p class="text-success"><strong>Pending</strong></p>';
                else
                    return '<p class="text-danger"><strong>Send</strong></p>';
            })
            ->editColumn('ref_type', function ($emails) {
                if ($emails->email_type == 'IN')
                    return 'Invoice';
                else if ($emails->email_type == 'QU')
                    return 'Quotation';
                else
                    return '';
            })
            ->editColumn('ref_id', function ($emails) {
                if (isset($emails->ref_id))
                    return $emails->ref_id;
                else
                    return '';
            })
            ->addColumn('id', function ($emails) {
                return $emails->id;
            })
            ->addColumn('mail_add', function ($emails) {
                return $emails->email;
            })
            ->addColumn('subject', function ($emails) {
                return $emails->subject;
            })
            ->addColumn('action', function ($emails) {
                return '<a class = "btn btn-info btn-xs" href="' . url("email_sender/" . $emails->id . "/edit") . '" id="edit_email" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('xemail_sender::create_email');
    }

    public function store(Request $request)
    {
        parse_str($request['email_details'], $email_details);

        $validator = Validator::make($email_details, [
            'email_address' => 'required',
            'mail_subject' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $path = NULL;


        if ($request['attachment'] == 'true') {
            $path = array();
            if ($email_details['ref_type'] == 'IN') {
                $path['invoice'] = GenerateInvoiceModel::generateInvoice($email_details['ref_id'], true);
            } elseif ($email_details['ref_type'] == 'QU') {
                $path['quotation'] = GenerateQuotationModel::generateQuotation($email_details['ref_id'], true);
            }
        }

        EmailSenderModel::saveMail($email_details ,$path );

        return response()->json(['status' => 'success', 'msg' => 'Success']);

    }



    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $email = EmailSenderModel::find($id);
        return view('xemail_sender::create_email')->with('email', $email);
    }

    public function update(Request $request, $id)
    {
        parse_str($request['email_details'], $email_details);

        $validator = Validator::make($email_details, [
            'email_address' => 'required',
            'mail_subject' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $path = NULL;
        if ($request['attachment'] == 'true') {
            $path = array();
            $path['invoice'] = GenerateInvoiceModel::generateInvoice(1);
            if ($email_details['ref_type'] == 'IN') {
                $path['invoice'] = GenerateInvoiceModel::generateInvoice($email_details['ref_id']);
            } elseif ($email_details['ref_type'] == 'QU') {
                $path['quotation'] = GenerateQuotationModel::generateQuotation($email_details['ref_id']);
            }
        }


        $email = EmailSenderModel::find($id);
        $email->email_type = $email_details['ref_type'];
        $email->ref_id = !empty($email_details['ref_id'] ?? null);
        $email->email = $email_details['email_address'];
        $email->subject = $email_details['mail_subject'];
        $email->mail_body = $email_details['mail_body'];
        if($path!=NULL)
            $email->attachments = json_encode($path);
        $email->send_time = Carbon::now();
        $email->sent_time = null;
        $email->status = 'P';
        $email->save();


        EmailSenderModel::SendMail();
        return response()->json(['status' => 'success', 'msg' => 'Success']);

    }

    public function destroy($id)
    {
        //
    }
}
