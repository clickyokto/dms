<?php

namespace Pramix\XCustomer\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Helper;
use App\Models\CustomerAddressesModel;
use App\Notifications\CustomerNotifications;
use Config;
use Countries;
use App\Rules\BranchUniqueValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGenaral\Models\CommentsModel;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use App;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CUSTOMERS');


        return view('xcustomer::all_customer_list');
    }


    public function create(Request $request)
    {

        Permission::checkPermission($request, 'ADD_CUSTOMER');

        $countryList = json_decode(Countries::getList(App::getLocale(), 'json'));
        $allow_comment = TRUE;
        $page = 'customer';
        return view('xcustomer::create_customer')
            ->with('countryList', $countryList)
            ->with('allow_comment', $allow_comment)
            ->with('page', $page);
    }

    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_CUSTOMER');

        parse_str($request['customer_details'], $customer_details); //This will convert the string to array
        parse_str($request['business_address_details'], $business_address_details);
        parse_str($request['shipping_address_details'], $shipping_address_details);

        if ($request['mobile'] != NULL)
            $customer_details['formated_mobile'] = $request['mobile'];
        if ($request['telephone'] != NULL)
            $customer_details['formated_telephone'] = $request['telephone'];

        if ($request['fax'] != NULL)
            $customer_details['formated_fax'] = $request['fax'];
        if ($customer_details['website'] != '')
            $customer_details['website'] = strpos($customer_details['website'], 'http') !== 0 ? "http://" . $customer_details['website'] . "" : $customer_details['website'];

        $customMessages = [
            'formated_mobile.phone' => 'Invalid Mobile No.',
            'formated_fax.phone' => 'Invalid Fax No.',
            'formated_telephone.phone' => 'Invalid Telephone No.',
        ];
        $validator = Validator::make($customer_details, [
            'business_name' => ['required', new BranchUniqueValidator(new CustomerModel(), 'business_name')],
            'formated_mobile' => ['phone:' . $request['mobile_country'], new BranchUniqueValidator(new CustomerModel(), 'mobile')],
            'formated_telephone' => ['phone:' . $request['telephone_country'], new BranchUniqueValidator(new CustomerModel(), 'telephone')],
            'formated_fax' => ['phone:' . $request['fax_country']],
            'email' => ['email', new BranchUniqueValidator(new CustomerModel(), 'email')],
            'nic' => ['max:20', new BranchUniqueValidator(new CustomerModel(), 'nic')],
            'title' => 'required',
            'website' => 'url',
            'rep' => ['required'],
            //'area' => ['required'],
            'invoice_type' => ['required']
        ], $customMessages);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }


        try {
            $customer = CustomerModel::storeCustomer($customer_details);
            AddressModel::saveAddresses($business_address_details, 'B', $customer->id, 'C');
            AddressModel::saveAddresses($shipping_address_details, 'S', $customer->id, 'C');
              return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'id' => $customer->id, 'business_name' => $customer->business_name, 'full_name' => $customer->fname . ' ' . $customer->lname]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => __('common.messages.save_error')]);

        }

    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        Permission::checkPermission($request, 'EDIT_CUSTOMER');

        $customer = CustomerModel::findOrFail($id);
        $countryList = json_decode(Countries::getList(App::getLocale(), 'json'));
        $business_address = AddressModel::where('ref_id', $id)->where('user_type', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'))->where('address_type', 'B')->first();

        $shipping_address = AddressModel::where('ref_id', $id)->where('user_type', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'))->where('address_type', 'S')->first();

        return view('xcustomer::create_customer')
            ->with('customer', $customer)
            ->with('shipping_address', $shipping_address)
            ->with('business_address', $business_address)
            ->with('countryList', $countryList);

    }


    public function update(Request $request, $id)
    {

        Permission::checkPermission($request, 'EDIT_CUSTOMER');

        parse_str($request['customer_details'], $customer_details); //This will convert the string to array
        parse_str($request['business_address_details'], $business_address_details);
        parse_str($request['shipping_address_details'], $shipping_address_details);

        if ($request['mobile'] != NULL)
            $customer_details['formated_mobile'] = $request['mobile'];
        if ($request['telephone'] != NULL)
            $customer_details['formated_telephone'] = $request['telephone'];
        if ($request['fax'] != NULL)
            $customer_details['formated_fax'] = $request['fax'];

        if ($customer_details['website'] != '')
            $customer_details['website'] = strpos($customer_details['website'], 'http') !== 0 ? "http://" . $customer_details['website'] . "" : $customer_details['website'];

        $customMessages = [
            'formated_mobile.phone' => 'Invalid Mobile No.',
            'formated_fax.phone' => 'Invalid Fax No.',
            'formated_telephone.phone' => 'Invalid Telephone No.',
        ];

        $validator = Validator::make($customer_details, [
            'business_name' => ['required', new BranchUniqueValidator(new CustomerModel(), 'business_name', $id)],
            'formated_mobile' => ['phone:' . $request['mobile_country'], new BranchUniqueValidator(new CustomerModel(), 'mobile', $id)],
            'formated_telephone' => ['phone:' . $request['telephone_country'], new BranchUniqueValidator(new CustomerModel(), 'telephone', $id)],
            'formated_fax' => ['phone:' . $request['fax_country']],
            'email' => ['email', new BranchUniqueValidator(new CustomerModel(), 'email', $id)],
            'nic' => ['max:20', new BranchUniqueValidator(new CustomerModel(), 'nic', $id)],
            'website' => 'url',
            'outstanding_limit' => 'required',
            'rep' => ['required'],
         //   'area' => ['required'],
            'invoice_type' => ['required']
        ], $customMessages);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        try {
            $customer = CustomerModel::storeCustomer($customer_details);
            AddressModel::saveAddresses($business_address_details, 'B', $customer->id, 'C');
            AddressModel::saveAddresses($shipping_address_details, 'S', $customer->id, 'C');

                  return response()->json(['status' => 'success', 'msg' => __('common.messages.update_successfully'), 'id' => $customer->id, 'business_name' => $customer->business_name, 'full_name' => $customer->fname . ' ' . $customer->lname]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => __('common.messages.save_error')]);
        }

    }

    public function destroy(Request $request, $id)
    {
        Permission::checkPermission($request, 'DELETE_CUSTOMER');

        $customer = CustomerModel::find($id);
        if ($customer->delete())
            return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
        else
            return response()->json(['status' => 'error', 'msg' => __('common.errors.can_not_delete_record_used_somewhere')]);
    }


    public function getCustomerList(Request $request)
    {
      //  Permission::checkPermission($request, 'VIEW_CUSTOMERS_LIST');
        $customers = CustomerModel::with('rep')->get();

        $delete_customer_permission = Auth::user()->can(['DELETE_CUSTOMER']);

        $edit_customer_permission = false;
        if (Auth::user()->can('EDIT_CUSTOMER')) {
            $edit_customer_permission = true;
        }

        return Datatables::of($customers)
            ->addColumn('action', function ($customers) use ($edit_customer_permission, $delete_customer_permission) {
                $actions = '';
                //  $actions = '<button class="btn btn-primary btn-sm customer_outstanding_button">Outstanding invoices</button>';

                if ($edit_customer_permission) {
                    $actions .= ' <a class = "btn btn-info btn-xs" href="' . url("/customer/" . $customers->id . "/edit") . '" id="edit_customer" data-original-title="" title=""><i class="fa fa-pencil"></i></a> <button class="btn btn-primary btn-xs" id="customer_history"><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
                if ($delete_customer_permission) {
                    $actions .= '&nbsp;<button  class="delete_customer btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                }
                return $actions;
            })
            ->addColumn('fullname', function ($customers) {
                $full_name = '';
                $full_name .= '<strong>'.$customers->company_name.'</strong><br>';
                    $full_name.= $customers->fullname;
                return $full_name;

            })
            ->addColumn('city', function ($customers) {
                return $customers->customerPrivateAddress->city->name_en ?? '';
            })
            ->addColumn('rep', function ($customers) {
                return $customers->rep->username;
            })
            ->addColumn('outstanding_amount', function ($customers) {
                return Helper::formatPrice($customers->outstanding_amount);
            })
            ->rawColumns(['fullname', 'action'])

            ->make(true);
    }


    public function getCustomerDetails(Request $request)
    {
        if ($request['phone'] != NULL)
            $request['formated_mobile'] = $request['phone'];
        if ($request['tel'] != NULL)
            $request['formated_telephone'] = $request['tel'];

        $validator = Validator::make($request->all(), [
            'formated_mobile' => 'phone:' . $request['mobile_country'],
            'formated_telephone' => 'phone:' . $request['telephone_country'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error']);

        }

        $customer = CustomerModel::Where('id', $request['id'])->orWhere('mobile', $request['phone'])->orWhere('telephone', $request['tel'])->first();

        $pending_cheques = ChequeModel::where('customer_id', $request['id'])->where('status', 0)->sum('payment_amount');

        if ($customer != NULL)
            return response()->json(['status' => 'success', 'msg' => __('Customer Details'), 'pending_cheques' => $pending_cheques, 'customer' => $customer]);
        else
            return response()->json(['status' => 'error']);
    }


    public function getCustomerBalanceDetails(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'customer_id' => 'required',
//        ]);

//        if (!$validator->passes()) {
//            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
//        }
        $customer_id = $request['customer_id'];
        $customer = CustomerModel::where('id', $customer_id)->first();

        return response()->json(['status' => 'success', 'msg' => __('Customer Details'), 'customer' => $customer ?? []]);

    }


    public function getSelectTwoCustomerNameFilter(Request $request)
    {
        $term = $request['term'];

        $customers = [];

        // if ($term != '')
        $customers = CustomerModel::where('company_name', 'like', '%' . $term . '%')->take(10)->get();

        return response()->json($customers);
    }

    public function getSelectTwoCustomerCodeFilter(Request $request)
    {
        $term = $request['term'];

        $customers = [];

        // if ($term != '')
        $customers = CustomerModel::where('business_name', 'like', '%' . $term . '%')->take(10)->get();

        return response()->json($customers);
    }


}
