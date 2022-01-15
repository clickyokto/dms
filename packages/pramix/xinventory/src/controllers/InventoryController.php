<?php

namespace Pramix\XInventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Pramix\XInventory\Models\Inventory;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Permission::checkPermission($request, 'INVENTORY_MANUAL_UPDATE');
        return view('xinventory::inventory');
    }

    public function getInventory()
    {

        $products = ProductsModel::with('category')->get();

        $inventory_manual_update_permission = false;
        if (Auth::user()->can('INVENTORY_MANUAL_UPDATE')) {
            $inventory_manual_update_permission = true;
        }

        return Datatables::of($products)
            ->addColumn('category', function ($products) {
                return $products->category->category_name ?? '';
            })
            ->addColumn('product', function ($products) {
                return $products->item_code .'<br>'. $products->description;
            })
            ->addColumn('qty_on_hand', function ($products) use ($inventory_manual_update_permission) {
                if ($inventory_manual_update_permission)
                    return '<a class="editable_field" href="#"   data-pk="' . $products->id . '" data-url="' . url("/change_store_qty/" . $products->id) . '" >' . Inventory::getProductStock($products->id) . '</a>';
                else
                    return Inventory::getProductStock($products->id);
            })
            ->addColumn('store', function ($products) {

             //   return $inventory->store->location ?? '';
            })
            ->editColumn('actions', function ($inventory) {
                return '';
            })
            ->rawColumns(['qty_on_hand', 'product_price','product'])
            ->make(true);



    }


    public function changeStoreQty(Request $request)
    {


        Permission::checkPermission($request, 'INVENTORY_MANUAL_UPDATE');
        $product = ProductsModel::find($request['pk']);

        Inventory::stockAdjustment($product->id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'manual_update'), '', $request['value']);

        return response()->json(['status' => 'success']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
