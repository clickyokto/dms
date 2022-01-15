<?php

namespace Pramix\XGRN\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\Templates\Models\GenerateGRNModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XGRN\Models\GRNModel;
use Pramix\XGRN\Models\GRNProductModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XProduction\Models\ProductionProductRelatedConsumersModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderProductsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use App\Http\Helper;

class GRNController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        return view('xgrn::grn.grn_list');
    }


    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_GRN');
        return view('xgrn::grn.create_grn');
    }

    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_GRN');

        $grn_details = GRNModel::storeGRN(NULL, 'D', $request[''], $request['remarks']);

        return response()->json(['status' => 'success', 'grn_details' => $grn_details]);

    }

    public function getPODetails(Request $request)
    {
        if ($request['purchase_order_id'] != '') {
            $purchase_order_id = $request['purchase_order_id'];

            $purchase_order = PurchaseOrderModel::find($purchase_order_id);
            $purchase_order_products = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->with('product')->get();

            $categories = array();
            foreach ($purchase_order_products as $purchase_order_product) {
                $categories[$purchase_order_product->product->category->id] = $purchase_order_product->product->category;
            }
            return response()->json(['status' => 'success', 'purchase_order' => $purchase_order, 'purchase_order_products' => $purchase_order_products, 'categories' => $categories]);
        }

    }


    public function show($id)
    {

    }


    public function edit($id, Request $request)
    {
        Permission::checkPermission($request, 'EDIT_GRN');
        $grn = GRNModel::find($id);
        $product_list = ProductsModel::where('type', 'stock')->pluck('item_code', 'id');
        $product_catagory = ProductCategoriesModel::pluck('category_name', 'id');

        return view('xgrn::grn.create_grn')
            ->with('product_catagory', $product_catagory)
            ->with('product_list', $product_list)
            ->with('grn', $grn);

    }


    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'ADD_GRN');
        $grn = GRNModel::find($id);
        $grn->supplier_id = 0;
        $grn->remarks = !empty($request['remarks']) ? $request['remarks'] : '';
        $grn->status = $request['status'];


        if ($grn->grn_code == '') {
            $last_record = GRNModel::orderBy('id', 'desc')->where('grn_code', '!=', '')->first();
            $grn->grn_code = OptionModel::generateCode('GRN', 4, $last_record->grn_code ?? NULL);
        }

        $grn->save();

        if ($request['status'] == 'A')
            GRNModel::approveGRN($id);


        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'grn' => $grn]);

    }


    public function destroy(Request $request,$id)
    {
                Permission::checkPermission($request, 'DELETE_GRN');

        $grn = GRNModel::find($id);
        if ($grn->delete())
            return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
        else
            return response()->json(['status' => 'error', 'msg' => __('common.errors.can_not_delete_record_used_somewhere')]);

    }

    public function getGRNList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        $grn = GRNModel::with('created_user')->where('grn_code', '!=', '')->get();
        $edit_grn_permission = false;
        if (Auth::user()->can('EDIT_GRN')) {
            $edit_grn_permission = true;
        }

        $delete_grn_permission = Auth::user()->can(['DELETE_GRN']);


        return Datatables::of($grn)
            ->addColumn('action', function ($grn) use ($edit_grn_permission,$delete_grn_permission) {
                $actions = '';
                if ($edit_grn_permission) {
                    $actions .=  '<a class = "btn btn-info btn-xs" href="' . url("/grn/" . $grn->id . "/edit") . '" id="edit_grn" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
                if ($delete_grn_permission && $grn->status == 'D') {
                    $actions .= '&nbsp;<button  class="delete_grn btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                }
                return $actions;
            })
            ->addColumn('user', function ($grn) {
                return $grn->created_user->username ?? '';
            })
            ->editColumn('status', function ($grn) {
                if ($grn->status == 'D')
                    return '<span class="text-danger"><strong>Draft</strong></span>';
                elseif ($grn->status == 'A')
                    return '<span class="text-success"><strong>Approved</strong></span>';

            })
            ->rawColumns(['status', 'action'])
            ->make(true);

    }


    public function getGRNProducts($order_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        if ($order_id == '') {
            $products = [];
        } else {
            $products = GRNProductModel::where('grn_id', $order_id)->with('store_location_name')->with('product')->get();
        }
        return Datatables::of($products)
            ->editColumn('actions', function ($products) {

                if ($products->status != 1)
                    return "<button class='btn btn-warning btn-xs  grn-product-edit'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs icon-circle-cross grn-delete'><i class='fa fa-remove'></i></button>";
                else
                    return "<span class='label label-success'>Completed</span>";
            })
            ->editColumn('stock_id', function ($products) {
                return $products->product->stock_id;
            })
            ->editColumn('item', function ($products) {
                return $products->product->item_code;
            })
            ->editColumn('store_location', function ($products) {
                return $products->store_location_name->location ?? '';
            })
            ->editColumn('sub_total', function ($products) {
                return $products->unit_price * $products->delivered_qty;
            })
            ->editColumn('quantity', function ($products) {
                return $products->qty;
            })
            ->rawColumns(['actions'])
            ->addIndexColumn()
            ->make(true);
    }


    public function deleteGRNProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        $id = $request['record_id'];

        GRNProductModel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'msg' => __('Success deleted')]);
    }

    public function addGRNProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        parse_str($request['product_details'], $product_details);

        $grn_id = $request['grn_id'];
        $recoed_id = $request['record_product_id'];


        if (!isset($grn_id) && $grn_id == '') {
            $grn_details = GRNModel::storeGRN(NULL, 'D', $request['']);

            $grn_id = $grn_details->id;
        } else {
            $grn_details = GRNModel::find($grn_id);
        }


        $validator = Validator::make($product_details, [
            'delivered_qty' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }


        $prod = GRNProductModel::where('grn_id', $grn_id)->where('id', $recoed_id)->first();

        if ($prod == NULL)
            $prod = new GRNProductModel();

        $delivered_qty = !empty($product_details['delivered_qty']) ? $product_details['delivered_qty'] : 0;
        $ordered_qty = 0;
        //  $unit_price = !empty($product['unit_price']) ? $product['unit_price'] : 0;

        $prod->grn_id = $grn_id;
        $prod->product_id = $product_details['products'];
        $prod->description = isset($product_details['description']) ? $product_details['description'] : '';
        $prod->delivered_qty = $delivered_qty;
        $prod->ordered_qty = $ordered_qty;
        $prod->unit_price = $product_details['unit_price'];
        $prod->selling_price = $product_details['selling_price'];
        $prod->store_id = 0;
        $prod->store_id = 0;
        //   $prod->sub_total = 0;
        $prod->status = 0;
        $prod->save();


        // $order = (PurchaseOrderModel::updateOrderPrice($order_id));
        return response()->json(['status' => 'success', 'msg' => __('Success Details'), 'grn_details' => $grn_details]);

    }

    public function generateGRNPDF(Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_GRN');
        $grn_id = $request['grn_id'];
        $pdf = GenerateGRNModel::generateGRN($grn_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }


    public function calOrderPrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN');
        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;


        parse_str($request['grn_price_details'], $grn_price_details);
        $grn_id = $request['grn_id'];

        if ($grn_id == '')
            return response()->json(['status' => 'error']);

        if (isset($grn_price_details['checked_vat'])) {
            $vat = true;
        }
        if (isset($grn_price_details['checked_nbt'])) {
            $nbt = true;
        }

        $grn = GRNModel::find($grn_id);

        $sub_tot = GRNProductModel::where('grn_id', $grn_id)->sum('sub_total');


        $discount = !empty($grn_price_details['grn_discount']) ? $grn_price_details['grn_discount'] : 0;
        $discount_type = !empty($grn_price_details['grn_discount_type']) ? $grn_price_details['grn_discount_type'] : 'P';


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

        $grn->vat_amount = $vat_amount;
        $grn->nbt_amount = $nbt_amount;
        $grn->sub_total = $sub_tot;
        $grn->discount = $discount;
        $grn->discount_type = $discount_type;
        $grn->total = $total;

        $grn->save();


        return response()->json(['status' => 'success', 'msg' => __('Record Save Success'), 'record' => $grn]);

    }

}
