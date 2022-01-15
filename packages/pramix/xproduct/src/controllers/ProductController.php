<?php

namespace Pramix\XProduct\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use App\Rules\BranchUniqueValidator;
use Carbon\Carbon;
use Config;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XMedia\Models\MediaModel;
use Pramix\XProduct\Models\ManufactureModel;
use Pramix\XProduct\Models\ProductDiscountsModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XProduct\Models\StoreLocationsModel;
use Pramix\XProjects\Models\ProjectsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PRODUCTS');
        return view('xproduct::product_list');
    }

    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PRODUCTS');
        return view('xproduct::create_product');
    }


    public function store(Request $request)
    {

        Permission::checkPermission($request, 'ADD_PRODUCTS');
        parse_str($request['basic_details'], $basic_details);
        parse_str($request['inventory_details'], $inventory_details);
        parse_str($request['cost_price'], $cost_price);
        parse_str($request['storage_info'], $storage_info);
        parse_str($request['measurement_details'], $measurement_details);
        parse_str($request['product_discount'], $product_discount);
        parse_str($request['product_pictures'], $product_pictures);

        $merge_array = array_merge($basic_details, $inventory_details, $cost_price, $storage_info, $measurement_details, $product_discount);


        $validator = Validator::make($merge_array, [
            'product_code' => ['required',new BranchUniqueValidator(new ProductsModel(), 'item_code')],
            'barcode' => [new BranchUniqueValidator(new ProductsModel(), 'barcode')],
            'product_category' => 'required',
            'qty' => 'gte:0'
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }


$manufacture = ManufactureModel::where('manufacture_name', $basic_details['manufacture'])->first();
        if($manufacture == NULL)
        {
            $manufacture = new ManufactureModel();
            $manufacture->manufacture_name = $basic_details['manufacture'];
            $manufacture->save();
        }


        $product = new ProductsModel();
        $product->stock_id = $basic_details['stock_id'];
        $product->item_code = $basic_details['product_code'];
        $product->type = $basic_details['product_type'];
        $product->category_id = $basic_details['product_category'];
        $product->qty_on_hand = 0;
        $product->description = $basic_details['description'];
        $product->barcode = isset($inventory_details['barcode']) ? $inventory_details['barcode'] : '';
        $product->reorder_point = ctype_digit($inventory_details['reorder_point']) ? $inventory_details['reorder_point'] : 0;
        $product->reorder_qty = ctype_digit($inventory_details['reorder_qry']) ? $inventory_details['reorder_qry'] : 0;
        $product->length = $measurement_details['length'];
        $product->width = $measurement_details['width'];
        $product->height = $measurement_details['height'];
        $product->weight = $measurement_details['weight'];
        $product->cost = floatval($cost_price['cost']);
        $product->store_location = $location->id ?? NULL;
        $product->price = floatval($cost_price['normal_price']);
        $product->status = 'A';
        $product->manufacture_id = $manufacture->id;
        $product->save();
//
       if ($product->type == 'stock') {
            Inventory::stockAdjustment($product->id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'stock_adjustment'), '', $inventory_details['qty_on_hand']);

        }
        if ($product_discount['discount_amount'] != "") {
            ProductDiscountsModel::saveDiscount($product->id, $product_discount);

        }

        if ($product_pictures['media_ids'] != '') {
            MediaModel::setMediaListRefID($product_pictures['media_ids'], $product->id);
            MediaModel::saveMediaOrder($product_pictures['media_ids'], getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), $product->id);
        }


        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'id' => $product->id, 'item_code' => $product->item_code]);

    }


    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        Permission::checkPermission($request, 'EDIT_PRODUCTS');
        $product = ProductsModel::with('discount')->find($id);

        $discount = ProductDiscountsModel::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where('product_id', '=', $id)
            ->where('limit', '>=', 0)->first();

        $manufacture = ManufactureModel::find($product->manufacture_id);

        return view('xproduct::create_product')
            ->with('media_array', MediaModel::getSortedMediaByRefID($id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media')))
            ->with('media_order', MediaModel::getMediaOrder($id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media')))
         ->with('manufacture' , $manufacture->manufacture_name ?? '')
            ->with('product', $product);

    }

    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'EDIT_PRODUCTS');
        parse_str($request['basic_details'], $basic_details);
        parse_str($request['inventory_details'], $inventory_details);
        parse_str($request['cost_price'], $cost_price);
        parse_str($request['storage_info'], $storage_info);
        parse_str($request['measurement_details'], $measurement_details);
        parse_str($request['product_discount'], $product_discount);
        parse_str($request['product_pictures'], $product_pictures);

        $merge_array = array_merge($basic_details, $inventory_details, $cost_price, $storage_info, $measurement_details, $product_discount);

       // dd($basic_details);

        $validator = Validator::make($merge_array, [
            'product_code' => ['required',  new BranchUniqueValidator(new ProductsModel(), 'item_code', $id)],
            'barcode' => [new BranchUniqueValidator(new ProductsModel(), 'barcode' , $id)],
            'product_category' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }
        $manufacture = ManufactureModel::where('manufacture_name', $basic_details['manufacture'])->first();
        if($manufacture == NULL)
        {
            $manufacture = new ManufactureModel();
            $manufacture->manufacture_name = $basic_details['manufacture'];
            $manufacture->save();
        }

        $product = ProductsModel::find($id);
        $product->item_code = $basic_details['product_code'];
        $product->type = $basic_details['product_type'];
        $product->stock_id = $basic_details['stock_id'];
        $product->category_id = $basic_details['product_category'];
        $product->description = $basic_details['description'];
        $product->barcode = isset($inventory_details['barcode']) ? $inventory_details['barcode'] : '';
        $product->reorder_point = ctype_digit($inventory_details['reorder_point']) ? $inventory_details['reorder_point'] : 0;
        $product->reorder_qty = ctype_digit($inventory_details['reorder_qry']) ? $inventory_details['reorder_qry'] : 0;
        $product->length = $measurement_details['length'];
        $product->width = $measurement_details['width'];
        $product->height = $measurement_details['height'];
        $product->store_location = $location->id ?? NULL;
        $product->weight = $measurement_details['weight'];
        $product->cost = floatval($cost_price['cost']);
        $product->price = floatval($cost_price['normal_price']);
        $product->status = 'A';
        $product->manufacture_id = $manufacture->id;
        $product->save();

        if ($product_discount['discount_amount'] != "") {
            ProductDiscountsModel::saveDiscount($product->id, $product_discount);
        }
        if ($product_pictures['media_ids'] != '') {
            MediaModel::setMediaListRefID($product_pictures['media_ids'], $product->id);
            MediaModel::saveMediaOrder($product_pictures['media_ids'], getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), $product->id);
        }
        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'id' => $product->id, 'item_code' => $product->item_code]);
    }


    public function destroy($id)
    {
        //
    }


    public function getProductList(Request $request)
    {
        $product = ProductsModel::with('category')->with('discount')->get();

        $edit_product_permission = false;
        if (Auth::user()->can('EDIT_PRODUCTS')) {
            $edit_product_permission = true;
        }

        return Datatables::of($product)
            ->addColumn('action', function ($product) use ($edit_product_permission) {
                if ($edit_product_permission) {
                    return '<a class = "btn btn-info btn-xs" href="' . url("/product/" . $product->id . "/edit") . '" id="edit_$product" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
            })
            ->addColumn('discount', function ($product) {
                if ($product->discount != null) {
                    if ($product->discount->start_date <= Carbon::now() && $product->discount->end_date >= Carbon::now()) {
                        if ($product->discount->discount_type == 'P')
                            return $product->discount->amount . '%';
                        else
                            return Helper::formatPrice($product->discount->amount);
                    }
                }

            })
            ->addColumn('category_name', function ($product) {
                return $product->category->category_name;
            })
            ->editColumn('qty_on_hand', function ($product) {
                return Inventory::getProductStock($product->id);
            })


            ->addColumn('image', function ($product) {
                $main_image = MediaModel::getMainImageWithFancyboxByRefID($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), 50);
                if ($main_image != '')
                    return $main_image;
                else
                    return '<i class="fa fa-product-hunt" aria-hidden="true"></i>';
            })
            ->addColumn('price', function ($product) {
                return Helper::formatPrice($product->price);
            })
            ->editColumn('type', function ($product) {
                if ($product->type == 'stock')
                    return __('xproduct::product.labels.stock');
                else if ($product->type == 'service')
                    return __('xproduct::product.labels.service');
                else if ($product->type == 'non_stock')
                    return __('xproduct::product.labels.non_stock');
                else if ($product->type == 'raw_material')
                    return __('xproduct::product.labels.raw_material');
                else if ($product->type == 'production')
                    return __('xproduct::product.labels.production');
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }


    public function getProductsByCategory(Request $request)
    {
        $category_id = $request['category_id'];
        if ($category_id != null)
        {
            if (isset($request['only_available_stock_product']) && $request['only_available_stock_product']=='true')
            {
                $products = ProductsModel::where('category_id', $category_id)->where(function($query)
                {$query->where('qty_on_hand','>',0)
                    ->orWhere('type','!=','stock');
                })->orderBy('id', 'asc')->get();
            }
            else {
                $products = ProductsModel::where('category_id', $category_id)->orderBy('id', 'asc')->get();
            }
        }
        else
        {
            if (isset($request['only_available_stock_product']) && $request['only_available_stock_product']=='true')
            {
                $products = ProductsModel::where(function($query)
                {$query->where('qty_on_hand','>',0)
                    ->orWhere('type','!=','stock');
                })->whereHas('category', function($q)
                {
                    $q->where('show_in_invoice', 1);
                })
                    ->orderBy('id', 'asc')->get();
            }
            else
                $products = ProductsModel::orderBy('id', 'asc')->get();
        }
        return response()->json(['status' => 'success', 'products' => $products]);

    }

    public function getProductDetails(Request $request)
    {

        $product_id = $request['product_id'];

        $main_image = MediaModel::getMainImageWithFancyboxByRefID($product_id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), 50);
        if ($main_image != '')
            $media = $main_image;
        else
            $media = '<i class="fa fa-product-hunt" aria-hidden="true"></i>';


        $product = ProductsModel::with('discount')->with('store_location')->find($product_id);
        $product->discount_amount = 0;
        $product->discount_type = 'P';

        if (isset($product->discount) && $product->discount != null) {
            if ($product->discount->start_date <= Carbon::now() && $product->discount->end_date >= Carbon::now()) {

                $product->discount_amount = $product->discount->amount;
                $product->discount_type = $product->discount->discount_type;

            }

        }

        $inventory = Inventory::where('product_id', $product_id)->where('active_status', 1)->first();

        Permission::checkPermission($request, 'CHANGE_INVOICE_PRODUCT_PRICE');

        if (Auth::user()->can('CHANGE_INVOICE_PRODUCT_PRICE')) {
            $price_edit  = true;
        }

        $stock =Inventory::getProductStock($product_id);

        return response()->json(['status' => 'success','available_stock'=> $stock, 'products' => $product,'inventory'=>$inventory, 'media' => $media, 'price_edit' => $price_edit ?? false]);

    }


    public function getProductDetailsByBarcode(Request $request)
    {

        $barcode = $request['barcode'];

        $product = ProductsModel::where('barcode', $barcode)->with('discount')->first();

        if ($product == NULL) {
            return response()->json(['status' => 'error']);
        }


        $main_image = MediaModel::getMainImageWithFancyboxByRefID($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), 50);
        if ($main_image != '')
            $media = $main_image;
        else
            $media = '<i class="fa fa-product-hunt" aria-hidden="true"></i>';


        $product->discount_amount = 0;
        $product->discount_type = 'P';

        if ($product->discount != null) {
            if ($product->discount->start_date <= Carbon::now() && $product->discount->end_date >= Carbon::now()) {

                $product->discount_amount = $product->discount->amount;
                $product->discount_type = $product->discount->discount_type;

            }

        }

        return response()->json(['status' => 'success', 'products' => $product, 'media' => $media]);
    }

    public function getManufactureList(Request $request)
    {
        $term = $request['term'];
        return response()->json(ManufactureModel::select('id', 'manufacture_name as name')->take(10)->where('manufacture_name', 'like', '%' . $term . '%')->get());

    }


}
