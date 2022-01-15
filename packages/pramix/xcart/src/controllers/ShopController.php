<?php

namespace Pramix\XCart\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XMedia\Models\MediaModel;
use Pramix\XProduct\Models\ProductsModel;
use Yajra\DataTables\DataTables;
use App\Http\Helper;
use Pramix\XInventory\Models\Inventory;
class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ProductsModel::paginate(18);

        return view('xcart::shop.index')->with('products', $products);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = ProductsModel::with('category')->with('discount')->find($id);
        return view('xcart::shop.product_view')
            ->with('media_array', MediaModel::getSortedMediaByRefID($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media')))
            ->with('media_order', MediaModel::getMediaOrder($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media')))
            ->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getCartProducts(Request $request)
    {
        $product = ProductsModel::with('category')->get();


        return Datatables::of($product)
            ->addColumn('action', function ($product)  {
return '<a href="javascript:void(0)" class="shop_product_view btn btn-primary">View</a>';
            })
            ->editColumn('category_name', function ($product) {
                return $product->category->category_name;
            })
            ->editColumn('item_code', function ($product) {
                $name = '';

                    $name .= '<small>'.$product->category->category_name.'</small><br>';

                $name.= '<strong>'.$product->item_code.'</strong>';
                return $name;
            })
            ->editColumn('qty_on_hand', function ($product) {
                return Inventory::getProductStock($product->id);
            })


            ->addColumn('image', function ($product) {
                $main_image = MediaModel::getMainImageWithFancyboxByRefID($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), 100);
                if ($main_image != '')
                    return $main_image;
                else
                    return '<i class="fa fa-product-hunt" aria-hidden="true"></i>';
            })
            ->addColumn('price', function ($product) {
                return Helper::formatPrice($product->price);
            })

            ->rawColumns(['image', 'action','item_code'])
            ->make(true);
    }
}
