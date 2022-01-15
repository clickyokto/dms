<?php

namespace Pramix\XPurchaseOrder\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Config;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\XSupplier\Models\SupplierModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderPaymentModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderProductsModel;
use Pramix\XMedia\Models\MediaModel;
use Pramix\XProduct\Models\ProductDiscountsModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XProduct\Models\StoreLocationsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;

class BillController extends Controller
{
    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_BILL_PAYMENT');
        $page = 'bill';
        return view('xpurchase_order::bill.create_bill')
            ->with('page', $page);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
//
    }

    public function update(Request $request, $id)
    {
//
    }


    public function destroy($id)
    {
        //
    }

    public function savePurchaseOrderPayment(Request $request)
    {
        Permission::checkPermission($request, 'ADD_BILL_PAYMENT');
        parse_str($request['bill_details'], $bill_details);
        $bill_amount = $bill_details['payment_amount'];
        $selected_purchase_orders = $request['purchase_orders_selected'];

        $validator = Validator::make($bill_details, [
            'payment_method' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

       asort($selected_purchase_orders);
        if ($bill_details['payment_method']== 'debit')
        {
            $supplier= SupplierModel::find($request['supplier_id']);
            if ($supplier->debit<$bill_amount)
            {
                return response()->json(['status' => 'error', 'msg' => 'Please check the debit']);
            }


        }
        foreach ($selected_purchase_orders as $purchase_order_id)
        {
            $purchase_order_bill_amount = 0;
            $purchase_order = PurchaseOrderModel::find($purchase_order_id);

            if ($bill_amount>= $purchase_order->balance)
            {
                $purchase_order_bill_amount = $purchase_order->balance;
                $bill_amount -= $purchase_order->balance;
            }
            else
            {
                $purchase_order_bill_amount = $bill_amount;
                $bill_amount = 0;
            }
            PurchaseOrderModel::savePurchaseOrderPayment($purchase_order_id, $purchase_order_bill_amount,$bill_details);

            if ($bill_amount==0)
                break;
        }

        if ($bill_amount>0)
        {
            $supplier= SupplierModel::find($request['supplier_id']);
            $supplier->debit += $bill_amount;
            $supplier->save();
        }

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully')]);

    }

    public function getPurchaseOrderBySupplier($supplier_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PURCHASE_ORDER');
        if ($supplier_id == '') {
            $purchase_order = [];
        } else {
            $purchase_order = PurchaseOrderModel::where('supplier_id', $supplier_id)->with('supplier')->get();
        }

        $edit_purchase_order_permission = false;
        if (Auth::user()->can('EDIT_INVOICE')) {
            $edit_purchase_order_permission = true;
        }

        return Datatables::of($purchase_order)
            ->addColumn('action', function ($purchase_order) use ($edit_purchase_order_permission) {
                if ($edit_purchase_order_permission) {
                    return '<a class = "btn btn-info btn-xs" href="' . url("/purchase_order/" . $purchase_order->id . "/edit") . '" id="edit_purchase_order" data-original-title="" title=""><i class="fa fa-pencil"></i></a> ';
                }
            })
            ->addColumn('created_by', function ($purchase_order) {
                return $purchase_order->user->username;
            })
            ->addColumn('supplier', function ($purchase_order) {
                if (isset($purchase_order->supplier->fullname))
                    return $purchase_order->supplier->fullname;
            })
            ->editColumn('bill_status', function ($purchase_order) {

                if ($purchase_order->total == $purchase_order->paid_amount)
                    return 'Completed';
                elseif ($purchase_order->total == $purchase_order->balance)
                    return 'Pending';
                elseif ($purchase_order->total != $purchase_order->balance)
                    return 'Partial';


            })
            ->editColumn('status', function ($purchase_order) {
                if ($purchase_order->status == 'D')
                    return 'Draft';
                elseif ($purchase_order->status == 'A')
                    return 'Completed';
                elseif ($purchase_order->status == 'C')
                    return 'Cancelled';

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

            ->make(true);
    }

    public function getPaymentList($purchase_order_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_BILL_PAYMENT');
        if ($purchase_order_id == '') {
            $bills = PurchaseOrderPaymentModel::with('purchase_order')->get();
        } else {
            $bills = PurchaseOrderPaymentModel::where('purchase_order_id', $purchase_order_id)->get();
        }
            return Datatables::of($bills)
                ->editColumn('action', function ($bills) {
//                    <button class='btn btn-success btn-sm icon-print bill-print-button' ></button>
                        return "<button class='btn btn-warning btn-sm bill-edit-button fa fa-eye' aria-hidden='true'></button> ";
                })
                ->editColumn('status', function ($bills) {
                    if ($bills->status != 0)
                        return '<span class="text-success"><strong>Completed</strong></span>';
                    else
                        return '<span class="text-danger"><strong>Cancelled</strong></span>';

                })
                ->addColumn('supplier', function ($bills) {
                        return $bills->purchase_order->supplier->fname.' '.$bills->purchase_order->supplier->lname;
                })
                ->addColumn('supplier_mobile', function ($bills) {
                    return $bills->purchase_order->supplier->mobile;
                })
                ->addColumn('purchase_order_id', function ($bills) {
                    return $bills->purchase_order->id;
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }


    public function viewPayment(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_BILL_PAYMENT');
        $bills = PurchaseOrderPaymentModel::with('purchase_order')->where('id', $request['id'] )->first();

        return view('xbill::bill_view')
            ->with('supplier',$bills->purchase_order->supplier)
            ->with('user',$bills->purchase_order->user)
            ->with('purchase_order',$bills->purchase_order)
            ->with('bills',$bills);
    }

    public function calPaymentePrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_BILL_PAYMENT');
        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;
        $purchase_order_id = $request['purchase_order_id'];

        if ($purchase_order_id != null) {

            $paid_amount = PurchaseOrderPaymentModel::where('purchase_order_id', $purchase_order_id)->where('status', 1)->sum('bill_amount');
            $sub_tot = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->sum('sub_total');
            $record = PurchaseOrderModel::where('id', $purchase_order_id)->first();


                if ($record['vat_amount']!=0) {
                    dd($record['vat_amount']);
                    $vat = true;
                }
                if ($record['nbt_amount']!=0) {
                    dd($record['nbt_amount']);
                    $nbt = true;
                }

                if ($record == NULL)
                    $record = new PurchaseOrderProductsModel();

                $discount = $record['discount'];
                $discount_type = $record['discount_type'];;


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

                SupplierModel::updateSupplierOutStanding($record->supplier_id);

            return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'record' => $record]);
        } else
            return 0;

    }


}
