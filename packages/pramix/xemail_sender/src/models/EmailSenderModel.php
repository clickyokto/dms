<?php

namespace Pramix\XEmailSender\Models;

use App\Scopes\BranchScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Pramix\Templates\Models\GenerateInvoiceModel;
use Pramix\Templates\Models\GenerateQuotationModel;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XEmailSender\Mail\SendMailSupport;

class EmailSenderModel extends Model
{
    protected $table = 'emails';
    protected $primaryKey = 'id';

    protected $casts = [
        'attachments' => 'array'
    ];


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScopes());

        static::creating(function ($model) {
            $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
            $model->branch_id = BranchesModel::getBranchID();
        });

        static::updating(function ($model) {
            $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });
    }

    public static function SendMail()
    {
        $emails = EmailSenderModel::where('status', 'P')->where('send_time', '<=', Carbon::now())->get();
        foreach ($emails as $email) {
            try {
                Mail::send(new SendMailSupport($email));
                $email->status = 'S';
                $email->save();
            } catch (\Exception $e) {
                $email->status = 'P';
                $email->save();

            }
        }
        return true;
    }

    public static function saveMail($email_details ,$attachments)
    {
        $email = new EmailSenderModel();
        $email->email_type = $email_details['ref_type'];
        $email->ref_id = $email_details['ref_id'];
        $email->email = $email_details['email_address'];
        $email->subject = $email_details['mail_subject'];
        $email->mail_body = $email_details['mail_body'];
        if($attachments!=NULL)
            $email->attachments = $attachments;
        $email->send_time = Carbon::now();
        $email->sent_time = null;
        $email->status = 'P';
        $email->save();

        $send_mail = EmailSenderModel::SendMail();
        return $send_mail;

    }



}
