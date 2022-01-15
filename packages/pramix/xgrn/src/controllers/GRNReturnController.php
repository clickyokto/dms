<?php

namespace Pramix\XGRN\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pramix\Templates\Models\GenerateGRNReturnModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XGRN\Models\GRNModel;
use Pramix\XGRN\Models\GRNProductModel;
use Pramix\XGRN\Models\GRNReturnModel;
use Pramix\XGRN\Models\GRNReturnProductModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;


class GRNReturnController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');
        $page = 'grn_return';
        return view('xgrn::grn_returns.returns_list')->with('page', $page);
    }


    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_GRN_RETURN');

        $page = 'grn_return';
        $grn_returns = '';
        $product_list = ProductsModel::where('type', '!=', 'production')->pluck('item_code', 'id');
        $product_catagory = ProductCategoriesModel::pluck('category_name', 'id');

        return view('xgrn::grn_returns.create_grn_return')
            ->with('product_catagory', $product_catagory)
            ->with('product_list', $product_list)
            ->with('page', $page)
            ->with('grn_returns', $grn_returns);
    }


    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_GRN_RETURN');

        $grn_return_code = OptionModel::generateCode('POR', 4, GRNReturnModel::orderBy('id', 'desc')->first());

        $grn_return_details = new GRNReturnModel();
        $grn_return_details->grn_return_code = '';
        $grn_return_details->grn_return_date = Carbon::now();
        $grn_return_details->supplier_id = $request['supplier_id'];
        $grn_return_details->grn_id = $request['grn_id'];
        $grn_return_details->status = 'D';

        $grn_return_details->save();

        return response()->json(['status' => 'success', 'order_details' => $grn_return_details]);
    }


    public function show($id)
    {
        //
    }


    public function edit($id, Request $request)
    {
        Permission::checkPermission($request, 'EDIT_GRN_RETURN');
        $page = 'grn_return';
        $grn_return = GRNReturnModel::find($id);

        return view('xgrn::grn_returns.create_grn_return')
            ->with('grn_return', $grn_return)
            ->with('page', $page);
    }


    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'ADD_GRN_RETURN');

        $grn_return_details = GRNReturnModel::find($id);
        $grn_return_details->supplier_id = $request['supplier_id'];
        $grn_return_details->grn_id = $request['grn_id'];
        $grn_return_details->remarks = $request['remarks'];
        if (!empty($request['status']))
        $grn_return_details->status = $request['status'];

        if ($grn_return_details->grn_return_code == '') {
            $last_record = GRNReturnModel::orderBy('id', 'desc')->where('grn_return_code', '!=', '')->first();
            $grn_return_details->grn_return_code = OptionModel::generateCode('GRNR', 4, $last_record->grn_return_code ?? NULL);
        }

        $grn_return_details->save();


        if ($request['status'] == 'A') {
            $grn_return_products = GRNReturnProductModel::where('grn_return_id', $id)->where('status', 0)->get();


            foreach ($grn_return_products as $grn_return_product) {
                $product = ProductsModel::find($grn_return_product->product_id);
                $grn_product = GRNProductModel::where('status', 1)->where('grn_id', $request['grn_id'])->where('product_id', $grn_return_product->product_id)->first();

                $available_stock = Inventory::getProductStock($product->id, $grn_return_product->store_id);


                if ($grn_product == NULL || $grn_product->delivered_qty < $grn_return_product->delivered_qty)
                    return response()->json(['status' => 'error', 'msg' => 'Invalid GRN Product Quantity']);

                if ($product->type == 'stock' && $available_stock < $grn_return_product->delivered_qty)
                    return response()->json(['status' => 'error', 'msg' => __('xinvoice::invoice.errors.no_stock')]);


            }

            foreach ($grn_return_products as $grn_return_product) {

                $product = ProductsModel::find($grn_return_product->product_id);

                $cost = 0;
                if ($product->type == 'stock') {
                    Inventory::decreaseInventory($grn_return_product->product_id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'purchase_returns'), $grn_return_details->grn_return_code, $grn_return_product->qty, NULL);

                }

                $grn_product = GRNProductModel::where('product_id', $grn_return_product->product_id)->where('grn_id', $grn_return_details->grn_id)->first();
                $cost = $grn_product->sub_total / $grn_product->delivered_qty;

                AverageCostModel::decreaseQtyCostUpdate($grn_return_product->product_id, $grn_return_product->delivered_qty, $cost);

                $grn_return_product->status = 1;
                $grn_return_product->save();


            }
        }
        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'order_details' => $grn_return_details]);
    }

    public function destroy($id)
    {
        //
    }


    public function generateGRNReturnPDF(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        $grn_return_id = $request['grn_return_id'];
        $pdf = GenerateGRNReturnModel::generateGRNReturn($grn_return_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }

    public function getGRN(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        if ($request['supplier_id'] != '')
            $grn = GRNModel::where('supplier_id', $request['supplier_id'])->get();

        else
            $grn = GRNModel::all();

        return response()->json(['status' => 'success', 'grn' => $grn]);
    }

    public function getGRNDetails(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        if ($request['grn_id'] != '') {
            $grn_id = $request['grn_id'];

            $grn = GRNModel::find($grn_id);
            $grn_products = GRNProductModel::where('grn_id', $grn_id)->with('product')->get();

            $categories = array();
            foreach ($grn_products as $grn_product) {
                $categories[$grn_product->product->category->id] = $grn_product->product->category;
            }

            return response()->json(['status' => 'success', 'grn' => $grn, 'grn_products' => $grn_products, 'categories' => $categories]);
        }

    }

    public function getGRNReturnProductDetails(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        if ($request['grn_id'] != '') {

            $grn_return_product_detail = GRNProductModel::where('grn_id', $request['grn_id'])->where('product_id', $request['product_id'])->with('product')->first();
            return response()->json(['status' => 'success', 'grn_return_product_detail' => $grn_return_product_detail]);
        }
    }


    public function addGRNReturnProduct(Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        parse_str($request['product_details'], $product);

        $grn_return_id = $request['grn_return_id'];
        $record_id = $request['record_product_id'];
        $product_id =$product['products'];
        $grn_id = $request['grn_id'];


        if ($record_id == NULL) {
            $grnr_prod = GRNReturnProductModel::where('grn_return_id', $grn_return_id)->where('product_id', $product['products'])->first();
            if ($grnr_prod != NULL)
                return response()->json(['status' => 'error', 'msg' => __('You have already added this product')]);
        }



        $grn_return = DB::table('grn_return_products')
            ->join('grn_return', 'grn_return_products.grn_return_id', '=', 'grn_return.id')
            ->where('grn_return.grn_id', $grn_id )
            ->where('grn_return_products.status', 1 )
            ->groupBy('product_id')
            ->sum('qty');
        $grn_product_qty= GRNProductModel::where('grn_id', $grn_id)->where('product_id', $product_id)->sum('delivered_qty');


        if ($grn_product_qty + $product['quantity'] <=$grn_return)
            return response()->json(['status' => 'error', 'msg' => __('You have already returned all products')]);


        $quantity = !empty($product['quantity']) ? $product['quantity'] : 0;
        $unit_price = !empty($product['unit_price']) ? $product['unit_price'] : 0;


        $product_data = ProductsModel::find($product['products']);
        $grn_product = GRNProductModel::where('status', 1)->where('grn_id', $request['grn_id'])->where('product_id', $product['products'])->first();

        if ($grn_product == NULL || $grn_product->delivered_qty < $quantity)
            return response()->json(['status' => 'error', 'msg' => 'Invalid GRN Product Quantity']);

        if ($product_data->type == 'stock' && $product_data->qty_on_hand < $quantity)
            return response()->json(['status' => 'error', 'msg' => __('xinvoice::invoice.errors.no_stock')]);


        $prod = GRNReturnProductModel::where('grn_return_id', $grn_return_id)->where('id', $record_id)->first();

        if ($prod == NULL)
            $prod = new GRNReturnProductModel();


        $prod->grn_return_id = $grn_return_id;
        $prod->product_id = $product['products'];
        $prod->description = isset($product['description']) ? $product['description'] : '';
        $prod->qty = !empty($product['quantity']) ? $product['quantity'] : 0;
        $prod->unit_price = !empty($product['unit_price']) ? $product['unit_price'] : 0;
        $prod->store_id = $product['store_location'];
        $prod->sub_total = $quantity * $unit_price;
        $prod->status = 0;
        $prod->save();


//        $order = (PurchaseOrderModel::updateOrderPrice($order_id));
        return response()->json(['status' => 'success', 'msg' => __('Record Save Success'), 'sub_total' => $grn_return_id]);

    }

    public function getProductsByCategory(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        $category_id = $request['category_id'];
        $grn_id = $request['grn_id'];

        if ($category_id != null) {
            $products = DB::table('grn_product')
                ->join('product', 'grn_product.product_id', '=', 'product.id')
                ->join('product_categories', 'product.category_id', '=', 'product_categories.id')
                ->select('product.*')
                ->where('product_categories.id', '=', $category_id)
                ->where('grn_product.grn_id', '=', $grn_id)
                ->get();
        } else {
            $products = DB::table('grn_product')
                ->join('product', 'grn_product.product_id', '=', 'product.id')
                ->where('grn_product.grn_id', '=', $grn_id)
                ->select('product.*')
                ->get();
        }


        return response()->json(['status' => 'success', 'products' => $products]);


    }

    public function getGRNReturnProduct($grn_return_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');
        if ($grn_return_id == '') {
            $products = [];
        } else {
            $products = GRNReturnProductModel::where('grn_return_id', $grn_return_id)->with('store_location_name')->with('product')->get();
        }

        return Datatables::of($products)

            ->editColumn('actions', function ($products) {
                if ($products->status != 1)
                    return "<button class='btn btn-warning btn-sm icon-edit'></button> <button class='btn btn-danger btn-sm icon-circle-cross'></button>";
                else
                    return "<span class='label label-success'>Completed</span>";
            })
            ->editColumn('category', function ($products) {
                return $products->product->category->category_name;
            })
            ->editColumn('item', function ($products) {
                return $products->product->item_code;
            })
            ->editColumn('store_location', function ($products) {
                return $products->store_location_name->location;
            })
            ->editColumn('sub_total', function ($products) {
                return $products->sub_total;
            })
            ->editColumn('quantity', function ($products) {
                return $products->qty;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function deleteGRNReturnProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        parse_str($request['order_price_details'], $price_details);
        $id = $request['product_id'];
        $order_id = $request['order_id'];

        GRNReturnProductModel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'msg' => __('Success deleted')]);

    }

    public function getGRNReturnList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        $grn_returns = GRNReturnModel::with('supplier')->with('user')->get();
        $edit_grnr_permission = false;
        if (Auth::user()->can('EDIT_GRN_RETURN')) {
            $edit_grnr_permission = true;
        }

        return Datatables::of($grn_returns)
            ->addColumn('action', function ($grn_returns) use ($edit_grnr_permission) {
                if ($edit_grnr_permission) {
                    return '<a class = "btn btn-info btn-xs" href="' . url("/grn_return/" . $grn_returns->id . "/edit") . '" id="edit_grnr" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
            })
            ->addColumn('supplier', function ($grn_returns) {
                if (isset($grn_returns->supplier->fullname))
                    return $grn_returns->supplier->fullname;
            })
            ->editColumn('status', function ($grn_returns) {
                if ($grn_returns->status == 'D')
                    return 'Draft';
                elseif ($grn_returns->status == 'A')
                    return 'Approved';

            })
            ->editColumn('total', function ($grn_returns) {
                return Helper::formatPrice($grn_returns->total);
            })
            ->make(true);

    }

    public function calOrderPrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_GRN_RETURN');

        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;

        parse_str($request['grn_return_price_details'], $grn_return_price_details);
        $grn_return_id = $request['grn_return_id'];

        if ($grn_return_id == '')
            return response()->json(['status' => 'success']);

        if (isset($grn_return_price_details['checked_vat'])) {
            $vat = true;
        }
        if (isset($grn_return_price_details['checked_nbt'])) {
            $nbt = true;
        }

        $grn_return_order = GRNReturnModel::find($grn_return_id);

        $sub_tot = GRNReturnProductModel::where('grn_return_id', $grn_return_id)->sum('sub_total');


        $discount = !empty($grn_return_price_details['grn_return_discount']) ? $grn_return_price_details['grn_return_discount'] : 0;
        $discount_type = !empty($grn_return_price_details['discount_type']) ? $grn_return_price_details['discount_type'] : 'P';


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

        $grn_return_order->vat_amount = $vat_amount;
        $grn_return_order->nbt_amount = $nbt_amount;
        $grn_return_order->sub_total = $sub_tot;
        $grn_return_order->discount = $discount;
        $grn_return_order->discount_type = $discount_type;
        $grn_return_order->total = $total;

        $grn_return_order->save();

        return response()->json(['status' => 'success', 'msg' => __('Record Save Success'), 'record' => $grn_return_order]);

    }
}
