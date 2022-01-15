<?php

namespace Pramix\XInvoice\Controllers;

use Illuminate\Http\Request;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;
use App\Http\Controllers\Controller;
use Countries;
use App;

class CustomerInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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


    public function invoiceRequestForPayment($invoice_id, Request $request)
    {
        $invoice = InvoiceModel::with('customer')->with('invoiceProducts')->where('id', $invoice_id)->first();
        $countryList = json_decode(Countries::getList(App::getLocale(), 'json'));

        $business_address = AddressModel::getAddress(($invoice['customer_id']), 'B', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'));
        $shipping_address = AddressModel::getAddress(($invoice['customer_id']), 'S', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'));

        return view('xinvoice::customer_view_invoice')
            ->with('invoice', $invoice)
            ->with('countryList', $countryList)
            ->with('customer', $invoice->customer)
            ->with('business_address', $business_address)
            ->with('invoiceproducts', $invoice->invoiceProducts);
    }

    public function saveInvoiceBillingAddress(Request $request)
    {

    }

    public function confirmPayment(Request $request)
    {
        $merchant_id         = $request['merchant_id'];
        $order_id             = $request['order_id'];
        $payhere_amount     = $request['payhere_amount'];
        $payhere_currency    = $request['payhere_currency'];
        $status_code         = $request['status_code'];
        $md5sig                = $request['md5sig'];
        $merchant_secret = '7925767da48b66d7d58e70ad3b098412'; // Replace with your Merchant Secret (Can be found on your PayHere account's Settings page)
        $local_md5sig = strtoupper (md5 ( $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret)) ) );
        if (($local_md5sig === $md5sig) AND ($status_code == 2) ){
            //TODO: Update your database as payment success
        }
    }
}
