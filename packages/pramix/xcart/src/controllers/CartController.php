<?php

namespace Pramix\XCart\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Illuminate\Http\Request;
use Cart;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XCart\Models\CartModel;
use \Pramix\XCart\Models\OrdersModel;
use \Pramix\XCart\Models\OrderItemsModel;
use Carbon\Carbon;
use Config;
use Auth;
use App\Notifications\SendInvoiceNotification;
use Pramix\XAdminpanel\Models\OrderCartsModel;
use Countries;
use App;
use Illuminate\Support\Facades\Validator;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XUser\Models\Permission;

use Yajra\DataTables\DataTables;

class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request)
    {
        $product_id = $request['product_id'];
        $qty = $request['qty'];

        if($qty == NULL || $qty == '' || $qty == 0)
            $qty = 1;

        CartModel::addToCart($product_id, $qty);
        return response()->json(['status' => 'success', 'cart_total'=>App\Http\Helper::formatPrice(Cart::getTotal())]);
    }

    public function removeFromCart(Request $request)
    {
        $item_id = $request['item_id'];

        Cart::remove($item_id);
        return response()->json(['status' => 'success', 'cart_total'=>App\Http\Helper::formatPrice(Cart::getTotal())]);
    }

    public function index()
    {

        $items = Cart::getContent();
        $customer = NULL;

        if(Auth::check())
        $customer = CustomerModel::where('user_account_id', auth()->user()->id)->first();
       return view('xcart::cart.cart')
            ->with('items', $items)
            ->with('customer', $customer);

    }

    public function getAddedProductList()
    {

        $items = Cart::getContent();
        $customer = NULL;

        if(Auth::check())
            $customer = CustomerModel::where('user_account_id', auth()->user()->id)->first();
        return Datatables::of($items)
            ->addColumn('action', function ($items) {
                $actions = '<button class="btn btn-xs btn-danger remove_item_from_cart" data-id="' . $items->id. '"><i class="fa fa-remove"></i></button>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//
    }




    public function getAddToRentModal()
    {
        return view('xcart::add_to_rent_modal');

    }

    public function clearCart()
    {
        Cart::clear();

        return response()->json(['status' => 'success', 'cart_total'=>App\Http\Helper::formatPrice(Cart::getTotal())]);

    }

    public function createInvoiceFromCart(Request $request)
    {
        Permission::checkPermission($request, 'ADD_INVOICE');


        $invoice_details = new InvoiceModel();
        $invoice_details->invoice_code = '';
        $invoice_details->invoice_date = Carbon::now();
        $invoice_details->customer_id = NULL;
        $invoice_details->invoice_company = getConfigValue('INVOICE_COMPANY');
        $invoice_details->vat_amount = 0;
        $invoice_details->nbt_amount = 0;
        $invoice_details->rep_id = NULL;
        $invoice_details->status = 'O';
        $invoice_details->save();




        $items = Cart::getContent();

        foreach ($items as $item) {
            $invoice_prod = new InvoiceProductsModel();
        $invoice_prod->invoice_id = $invoice_details->id;
        $invoice_prod->product_id = $item->id;
        $invoice_prod->description = '';
        $invoice_prod->qty = $item->quantity;
        $invoice_prod->unit_price = $item->price;
        $invoice_prod->discount = 0;
        $invoice_prod->discount_type = 'percentage';
        $invoice_prod->sub_total = $item->quantity * $item->price;
        $invoice_prod->store_id = 0;
        $invoice_prod->cost = 0;
        $invoice_prod->status = 0;
        $invoice_prod->save();
    }




        $invoice = InvoiceModel::updateInvoicePrice($invoice_details->id);

        return response()->json(['status' => 'success', 'invoice_details' => $invoice_details]);
    }


}
