<?php

namespace Pramix\XPurchaseOrder\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Countries;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\Templates\Models\GeneratePurchaseOrderModel;
use Pramix\Templates\Models\GeneratePaymentPrintModel;
use Pramix\XGRN\Models\GRNModel;
use Pramix\XSupplier\Models\SupplierModel;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderPaymentModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderProductsModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderMail;


class PurchaseOrderController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');

        $page = 'purchase_order';

        return view('xpurchase_order::purchase_order_list')
            ->with('page', $page);
    }


    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PURCHASE_ORDER');

        $countryList = json_decode(Countries::getList(App::getLocale(), 'json'));
        $allow_comment = TRUE;
        $page = 'purchase_order';
        $products = 'products';
        $product_categories = ProductCategoriesModel::all();
        return view('xpurchase_order::create_purchase_order')
            ->with('countryList', $countryList)
            ->with('allow_comment', $allow_comment)
            ->with('page', $page)
            ->with('products', $products)
            ->with('product_categories', $product_categories);

    }


    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PURCHASE_ORDER');

        if ($request['supplier_id'] != '') {

            $purchase_order_details = new PurchaseOrderModel();
            $purchase_order_details->purchase_order_code = '';
            $purchase_order_details->purchase_order_date = Carbon::now();
            $purchase_order_details->supplier_id = $request['supplier_id'];
            $purchase_order_details->vat_amount = 0;
            $purchase_order_details->nbt_amount = 0;
            $purchase_order_details->status = 'D';
            $purchase_order_details->save();

            return response()->json(['status' => 'success', 'purchase_order_details' => $purchase_order_details]);
        }

    }


    public function show($id, Request $request)
    {

    }


    public function edit($id, Request $request)
    {
        Permission::checkPermission($request, 'EDIT_PURCHASE_ORDER');
        $checked_recurring_status = null;
        $purchase_order = PurchaseOrderModel::find($id);

        $product_list = ProductsModel::where('type', '!=', 'production')->pluck('item_code', 'id');
        $product_catagory = ProductCategoriesModel::pluck('category_name', 'id');

        $page = 'purchase_order';

        return view('xpurchase_order::create_purchase_order')
            ->with('product_catagory', $product_catagory)
            ->with('product_list', $product_list)
            ->with('purchase_order', $purchase_order)
            ->with('checked_recurring_status', $checked_recurring_status)
            ->with('page', $page);
    }


    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'ADD_PURCHASE_ORDER');
        parse_str($request['recurring_details'], $recurring_details);
        $purchase_order_details = PurchaseOrderModel::find($id);

        $status = $request['status'];

        if ($status == 'A') {

            $purchase_order_products = PurchaseOrderProductsModel::where('purchase_order_id', $id)->where('status', 0)->get();

            foreach ($purchase_order_products as $purchase_order_product) {
                $purchase_order_product->status = 1;
                $purchase_order_product->save();
            }

        }

        if ($purchase_order_details->purchase_order_code == '') {
            $last_record = PurchaseOrderModel::orderBy('id', 'desc')->where('purchase_order_code', '!=', '')->first();
            $purchase_order_details->purchase_order_code = OptionModel::generateCode('PO', 4, $last_record->purchase_order_code ?? NULL);
        }

        $purchase_order_details->assigned_user = !empty($request['staff_id']) ? ($request['staff_id']) : null;
        $purchase_order_details->supplier_id = !empty($request['supplier_id']) ? ($request['supplier_id']) : 0;
        $purchase_order_details->remarks = $request['remarks'];
        if ($purchase_order_details->status != 'A') {
            $purchase_order_details->status = $status;
        }
         $purchase_order_details->save();

        SupplierModel::updateSupplierOutStanding($purchase_order_details->supplier_id);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'purchase_order_details' => $purchase_order_details]);
    }

    public function getGRNHistoryModal($purchase_order_id, Request $request)
    {
        return view('xpurchase_order::grn_list_model')
            ->with('purchase_order_id', $purchase_order_id);
    }

    public function getGRNHistoryList($purchase_order_id, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        $grn = GRNModel::where('grn_code','!=','')->where('purchase_order_id',$purchase_order_id)->with('supplier')->get();

        $edit_grn_permission = false;
        if (Auth::user()->can('EDIT_GRN')) {
            $edit_grn_permission = true;
        }

        return Datatables::of($grn)
            ->addColumn('action', function ($grn)  use ($edit_grn_permission) {
                if ($edit_grn_permission) {
                    return '<a class = "btn btn-info btn-xs" target="_blank" href="' . url("/grn/" . $grn->id . "/edit") . '" id="edit_grn" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
            })
            ->addColumn('supplier', function ($grn) {
                if (isset($grn->supplier))
                    return $grn->supplier->fname;
            })
            ->editColumn('status', function ($grn) {
                if ($grn->status == 'D')
                    return 'Draft';
                elseif ($grn->status == 'A')
                    return 'Approved';

            })
            ->make(true);
    }

    public function destroy($id)
    {
        //
    }

    public function addPurchaseOrderProduct(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PURCHASE_ORDER');

        parse_str($request['product_details'], $product_details);

        $purchase_order_id = $request['purchase_order_id'];
        $record_id = $request['record_product_id'];
        $qty = !empty($product_details['quantity']) ? $product_details['quantity'] : 1;

        if ($record_id == NULL) {
            $purchase_order_prod = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->where('product_id', $product_details['products'])->first();

            if ($purchase_order_prod != NULL)
                return response()->json(['status' => 'error', 'msg' => __('You have already added this product')]);
        }

        $validator = Validator::make($product_details, [
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $product = ProductsModel::find($product_details['products']);


        $purchase_order_prod = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->where('id', $record_id)->first();

        if ($purchase_order_prod == NULL)
            $purchase_order_prod = new PurchaseOrderProductsModel();

        $quantity = !empty($product_details['quantity']) ? $product_details['quantity'] : 0;
        $unit_price = !empty($product_details['unit_price']) ? $product_details['unit_price'] : 0;
        $discount = !empty($product_details['discount']) ? $product_details['discount'] : 0;
        if ($product_details['discount_type'] == 'P') {
            $tot = $quantity * $unit_price;
            $sub_tot = $tot * (100 - $discount) / 100;

        } else {
            $tot = $quantity * $unit_price;
            $sub_tot = $tot - $discount;

        }

        $purchase_order_prod->purchase_order_id = $purchase_order_id;
        $purchase_order_prod->product_id = $product_details['products'];
        $purchase_order_prod->description = isset($product_details['description']) ? $product_details['description'] : '';
        $purchase_order_prod->qty = $qty;
        $purchase_order_prod->unit_price = !empty($product_details['unit_price']) ? $product_details['unit_price'] : 0;
        $purchase_order_prod->discount = $discount;
        $purchase_order_prod->discount_type = isset($product_details['discount_type']) ? $product_details['discount_type'] : 'P';
        $purchase_order_prod->sub_total = $sub_tot;
        $purchase_order_prod->type = 'PO';
        $purchase_order_prod->status = 0;
        $purchase_order_prod->save();

        $purchase_order = PurchaseOrderModel::updatePurchaseOrderPrice($purchase_order_id);
        return response()->json(['status' => 'success', 'msg' => __('Success Details'), 'sub_total' => $purchase_order->sub_total]);

    }

    public function getPurchaseOrderProducts($purchase_order_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        if ($purchase_order_id == '') {
            $products = [];
        } else {
            $products = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->with('product')->get();
        }

        return Datatables::of($products)
            ->editColumn('discarded', function ($products) {

            })
            ->editColumn('category', function ($products) {
                return $products->product->category->category_name;
            })
            ->editColumn('item', function ($products) {
                return $products->product->item_code;
            })
            ->editColumn('actions', function ($products) {
                if ($products->status != 1)
                    return "<button class='btn btn-warning btn-sm icon-edit purchase_order_product_edit_btn' id=''></button> <button class='btn btn-danger btn-sm icon-circle-cross purchase_order_product_delete_btn'></button>";
                else
                    return "<span class='label label-success'>Completed</span>";
            })
            ->editColumn('sub_total', function ($products) {
                return Helper::formatPrice($products->sub_total);
            })
            ->editColumn('discount_type_show', function ($products) {
                if ($products->discount_type == 'P')
                    return 'Percentage';
                else
                    return 'Amount';
            })
            ->editColumn('quantity', function ($products) {
                return $products->qty;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function calPurchaseOrderPrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;

        if ($request['purchase_order_id'] != null) {
            $purchase_order_id = $request['purchase_order_id'];
            $paid_amount = PurchaseOrderPaymentModel::where('purchase_order_id', $purchase_order_id)->where('status', 1)->sum('payment_amount');
            $sub_tot = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->sum('sub_total');
            $record = PurchaseOrderModel::where('id', $purchase_order_id)->first();

            if ($request['ref_type'] == 'PAY') {

                $balance = $record['total'] - $paid_amount;
                $record->sub_total = $sub_tot;
                $record->paid_amount = round($paid_amount, 2);
                $record->balance = round($balance, 2);
                $record->save();

//                SupplierModel::updateSupplierOutStanding($record->supplier_id);
            } else {
                parse_str($request['purchase_order_price_details'], $prices);

                if (isset($prices['checked_vat'])) {
                    $vat = true;
                }
                if (isset($prices['checked_nbt'])) {
                    $nbt = true;
                }

                if ($record == NULL)
                    $record = new PurchaseOrderProductsModel();

                $discount = !empty($prices['purchase_order_discount']) ? $prices['purchase_order_discount'] : 0;
                $discount_type = !empty($prices['discount_type']) ? $prices['discount_type'] : 0;


                if ($discount_type == 'P') {
                    $total = $sub_tot * (100 - $discount) / 100;
                } else {
                    $total = $sub_tot - $discount;
                }

                if ($vat) {
                    $vat_amount = $total * (getConfig('VAT_PERCENTAGE') / 100);
                }
                if ($nbt) {
                    $nbt_amount = $total * (getConfig('NBT_PERCENTAGE') / 100);
                }

                $total = $total + $vat_amount + $nbt_amount;
                if ($paid_amount != 0) {
                    $balance = $total - $paid_amount;
                } else
                    $balance = $total;

                $record->sub_total = $sub_tot;
                $record->discount = $discount;
                $record->discount_type = $discount_type;
                $record->vat_amount = $vat_amount;
                $record->nbt_amount = $nbt_amount;
                $record->total = round($total, 2);
                $record->paid_amount = round($paid_amount, 2);
                $record->balance = round($balance, 2);
                $record->save();

//                SupplierModel::updateSupplierOutStanding($record->supplier_id);
            }
            return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'record' => $record]);
        } else
            return 0;

    }


    public function deletePurchaseOrderProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        parse_str($request['purchase_order_price_details'], $price_details);
        $id = $request['record_id'];
        $purchase_order_id = $request['purchase_order_id'];

        PurchaseOrderProductsModel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
    }

    public function getSalesPayments($filter_id = '', $filter_type = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_BILL_PAYMENT');

//        $edit_payment_permission = false;
//        if (Auth::user()->can('EDIT_PAYMENT')) {
//            $edit_inquiry_permission = true;
//        }
//        $delete_payment_permission = false;
//        if (Auth::user()->can('DELETE_PAYMENT')) {
//            $delete_payment_permission = true;
//        }
        if ($filter_type == '') {
            if ($filter_id == '')
            {
                $payments = [];
            }
            else
            {
                $payments = PurchaseOrderPaymentModel::where('purchase_order_id', $filter_id)->with('purchase_order')->get();
            }

        } else
            {
            $payments = PurchaseOrderPaymentModel::whereHas('purchase_order', function ($q) use($filter_id) {
                $q->where('supplier_id', $filter_id);
            })->with('purchase_order')->get();
        }

        if ($payments != '') {
            return Datatables::of($payments)
                ->editColumn('actions', function ($payments) {

                    if ($payments->status != 0) {
                        $edit_button = '';
                        $detele_button = '';
//                        if ($edit_inquiry_permission) {
//                            $edit_button = "<button class='btn btn-warning btn-sm icon-edit payment-edit-button'></button>";
//                        }
//                        <button class='btn btn-success btn-sm icon-print payment-print-button' ></button>
                        return $edit_button . ' ' . $detele_button . ' ' . "";
                    } else
                        return "<span class='label label-danger'>Cancelled</span>";

                })
                ->addColumn('purchase_order_code', function ($payments)  {
                    return $payments->purchase_order->purchase_order_code;

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function savePurchaseOrderPayment(Request $request)
    {
        Permission::checkPermission($request, 'ADD_BILL_PAYMENT');

        $purchase_order_id = $request['purchase_order_id'];
        parse_str($request['payment_details'], $payment_details);
        $id = $request['record_payment_id'];

        $validator = Validator::make($payment_details, [
            'payment_method' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $payment = PurchaseOrderModel::savePurchaseOrderPayment($purchase_order_id, $payment_details['payment_amount'] ,$payment_details);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'payment' => $payment]);

    }


//    public function deletePayment(Request $request)
//    {
//        Permission::checkPermission($request, 'DELETE_PAYMENT');
//
//        parse_str($request['payment_details'], $payment_details);
//        $id = $request['record_payment_id'];
//
//        $purchase_order_id = $request['purchase_order_id'];
//        $purchase_order = PurchaseOrderModel::find($purchase_order_id);
//        $payment = PurchaseOrderPaymentModel::where('id', $id)->first();
//        $payment->status = 0;
//        $payment->save();
//
////        SupplierModel::updateSupplierOutStanding($purchase_order->supplier_id);
//        return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
//    }

    public function printPayment(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_BILL_PAYMENT');
        $id = $request['record_payment_id'];
        $purchase_order_id = $request['purchase_order_id'];

        $pdf = GeneratePaymentPrintModel::generatePaymentPrint($id, $purchase_order_id);
        return response()->json(['status' => 'success', 'url' => $pdf]);

    }

    public function createMailModel($purchase_order_id = NULL, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        $purchase_order = PurchaseOrderModel::with('supplier')->where('id', $purchase_order_id)->first();
        $email = array();
        $email['ref_id'] = $purchase_order->id;
        $email['mail_type'] = 'IN';

        return view('xemail_sender::create_email')
            ->with('supplier_mail', $purchase_order->supplier->email)
            ->with('supplier_name', $purchase_order->supplier->fullname)
            ->with('email', $email);

    }

//    public function purchase_orderSendMail(Request $request)
//    {
//        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
//
//        $this->validate($request, [
//            'name' => 'required',
//            'email' => 'required|email',
//            'message' => 'required'
//
//        ]);
//
//        if ($request['attachment'] == 'true') {
//            $path = GeneratePurchaseOrderModel::generatePurchaseOrder($request['id'],true );
//            $data = array(
//                'name' => $request->name,
//                'message' => $request->message,
//                'path' => $path
//
//            );
//        } else
//            $data = array(
//                'name' => $request->name,
//                'message' => $request->message,
//            );
//
//
//        Mail::to($request->email)->send(new PurchaseOrderMail($data));
//
//        return response()->json(['status' => 'success', 'msg' => __('Email send successfully')]);
//
//    }

    public function getProductCreateModel(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PRODUCT');
        return view('xproduct::create_product');
    }


    public function getPurchaseOrderList($filter_id = NULL, $filter_by = NULL, $page = NULL, Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');

        $purchase_order = PurchaseOrderModel::where('purchase_order_code','!=','')->with('supplier')->with('user');

        if ($filter_id != null && $filter_by == 'project') {
            $purchase_order->where('project_id', $filter_id);
        }
        if ($filter_id != null && $filter_by == 'supplier') {
            $purchase_order->where('status', 'A');
            $purchase_order->where('supplier_id', $filter_id);
        }
        if ($page == 'payment') {
            $purchase_order->where('balance', '>', 0);
        }
        $purchase_order = $purchase_order->get();


        $edit_purchase_order_permission = false;
        if (Auth::user()->can('EDIT_PURCHASE_ORDER')) {
            $edit_purchase_order_permission = true;
        }

        return Datatables::of($purchase_order)
            ->addColumn('action', function ($purchase_order) use ($edit_purchase_order_permission, $page) {
                if ($edit_purchase_order_permission) {
                    $button = '';
                    return '<a class = "btn btn-info btn-xs" href="' . url("/purchase_order/" . $purchase_order->id . "/edit") . '" id="edit_purchase_order" data-original-title="" title=""><i class="fa fa-pencil"></i></a> ' . '' . $button;
                }
            })
            ->addColumn('created_by', function ($purchase_order) {
                return $purchase_order->user->username;
            })
            ->addColumn('supplier', function ($purchase_order) {
                if (isset($purchase_order->supplier->fullname))
                    return $purchase_order->supplier->fullname;
            })
            ->editColumn('payment_status', function ($purchase_order) {

                if ($purchase_order->total <= $purchase_order->paid_amount)
                    return '<span class="text-success">Completed</span>';
                elseif ($purchase_order->total == $purchase_order->balance)
                    return '<span class="text-danger"><strong>Pending</strong></span>';
                elseif ($purchase_order->total != $purchase_order->balance)
                    return '<span class="text-primary">Partial</span>';


            })
            ->editColumn('status', function ($purchase_order) {
                if ($purchase_order->status == 'D')
                    return '<span class="text-danger"><strong>Draft</strong></span>';
                elseif ($purchase_order->status == 'A')
                    return '<span class="text-success">Completed</span>';
                elseif ($purchase_order->status == 'C')
                    return '<span class="text-primary">Cancelled</span>';

            })
            ->editColumn('total', function ($purchase_order) {
                return Helper::formatPrice($purchase_order->total);
            })
            ->editColumn('paid_amount', function ($purchase_order) {
                return Helper::formatPrice($purchase_order->paid_amount);
            })
            ->editColumn('balance', function ($purchase_order) {
                return Helper::formatPrice($purchase_order->balance);
            })
            ->rawColumns(['payment_status', 'status', 'action'])
            ->make(true);

    }

    public function generatePurchaseOrderPDF(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');

        $purchase_order_id = $request['purchase_order_id'];
        $pdf = GeneratePurchaseOrderModel::generatePurchaseOrder($purchase_order_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }


    public function getSupplierHistoryModal($supplier_id, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        $supplier = SupplierModel::findOrFail($supplier_id);

        return view('xpurchase_order::supplier_history_list')
            ->with('supplier_id', $supplier_id)
            ->with('supplier', $supplier);
    }

    public function duplicatePurchaseOrder(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        $new_purchase_order_id = PurchaseOrderModel::duplicatePurchaseOrder($request['purchase_order_id']);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'purchase_order_no' => $new_purchase_order_id]);

    }
}
