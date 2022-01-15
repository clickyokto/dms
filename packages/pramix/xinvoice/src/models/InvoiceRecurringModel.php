<?php
namespace Pramix\XInvoice\Models;
use App\Scopes\BranchScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Pramix\Templates\Models\GenerateInvoiceModel;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XEmailSender\Models\EmailSenderModel;
class InvoiceRecurringModel extends Model
{
    protected $table = 'invoice_recurring';
    protected $primaryKey = 'id';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
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
    public static function generateRecurringInvoice()
    {
        $yearly_month = Carbon::today()->format('m');
        $monthly_date = Carbon::today()->format('d');
        $email_details = array();
        $email_details['email_address'] = getConfigArrayValueByKey('COMPANY_DETAILS', 'email') ?? '';
        $email_details['mail_subject'] = 'Recurring Invoice';
        $email_details['mail_body'] = 'Test';
        $email_details['ref_type'] = 'IN';
        $monthly_recurring_invoices = DB::table('invoice_recurring')
            ->where('status', 1)
            ->where('billing_cycle', 'M')
            ->where('monthly_generated_date', '<=', $monthly_date)
            ->where(function ($query){
                $query->whereYear('last_generated_date', '!=', Carbon::today()->format('Y'));
            })
            ->orWhere(function ($query){
                $query->whereYear('last_generated_date', Carbon::today()->format('Y'));
                $query->whereMonth('last_generated_date', '!=', Carbon::today()->format('m'));
            })->get();
        foreach ($monthly_recurring_invoices as $monthly_recurring_invoice) {
            $path = array();
            $new_invoice_id = InvoiceModel::duplicateInvoice($monthly_recurring_invoice->invoice_id);
            $monthly_recurring_invoice->last_generated_date = Carbon::now();
            $monthly_recurring_invoice->save();
            $email_details['ref_id'] = $new_invoice_id;
            EmailSenderModel::saveMail($email_details, $path);
        }
        $yearly_recurring_invoices = InvoiceRecurringModel::where('status', 1)->where('billing_cycle', 'Y')->where('yearly_generated_month', '<=', $yearly_month)->where('monthly_generated_date', '<=', $monthly_date)->whereYear('last_generated_date', '!=', Carbon::today()->format('Y'))->get();
        foreach ($yearly_recurring_invoices as $yearly_recurring_invoice) {
            $path = array();
            $new_invoice_id = InvoiceModel::duplicateInvoice($yearly_recurring_invoice->invoice_id);
            $yearly_recurring_invoice->last_generated_date = Carbon::now();
            $yearly_recurring_invoice->save();
            $email_details['ref_id'] = $new_invoice_id;
            EmailSenderModel::saveMail($email_details, $path);
        }
        return response()->json(['status' => 'success', 'msg' => __('Email send successfully')]);
    }
}
