@extends('xinvoice::customer_invoice_layout')

{{--@section('title', Helper::formatTitle(__('invoice.titles.invoice_list')))--}}

@section('include_css')
    <!-- Data Tables -->


@endsection

@section('content')

<input type="hidden" name="invoice_code" id="invoice_code" value="{{$invoice->invoice_code ?? ''}}">
<input type="hidden" name="amount_due" id="amount_due" value="{{$invoice->balance ?? ''}}">
<input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice->id ?? ''}}">


    <div class="row">
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <h4 class="text-primary "><strong>Request for Payment from PRAMIX IT</strong></h4>

            <div class="card" id="invoice_panel">
                <div class="card-header">
                    <!-- Row starts -->
                    <div class="row gutter">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <img src="{{asset('/images/logo_pramix.jpg')}}" alt="Pramix Logo"
                                 class="logo img-responsive"/>


                        </div>
                        <div class="col-md-8 col-sm-2 col-xs-12">
                            <h5><strong>PRAMIX IT Solutions</strong></h5>
                            <p>No.222/A, Kurukulawa, Ragama, Gampaha, Sri Lanka</p>
                            <p>Mobile : +94 711 252 282 | Web : <a href="www.pramixit.com" target="_blank">www.pramixit.com</a>
                                | Email : info@pramixit.com</p>


                        </div>
                    </div>
                    <!-- Row ends -->
                </div>
                <div class="card-body">


                    <!-- Row starts -->
                    <div class="row gutter">
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <address class="right-text">
                                <h5><strong>{{$customer->fullname ?? ''}}</strong></h5>

                                @if(isset($business_address->address_line_1) && $business_address->address_line_1 != '')
                                    <br> {{$business_address->address_line_1 ?? ''}},
                                @endif
                                @if(isset($business_address->address_line_2) && $business_address->address_line_2 != '')
                                    <br> {{$business_address->address_line_2 ?? ''}},
                                @endif
                                @if(isset($business_address->city_name) && $business_address->city_name != '')
                                    <br> {{$business_address->city_name ?? ''}},
                                @endif
                                @if(isset($business_address->district_name) && $business_address->district_name != '')
                                    <br>{{$business_address->district_name ?? ''}}, </abbr> <br>
                                @endif
                                @if(isset($customer->email) && $customer->email != '')
                                    <abbr title="email">{{ __('xinvoice::invoice.labels.email')}}:</abbr>
                                    <a href="mailto:#" data-original-title="" title="">{{$customer->email ?? ''}}</a>
                                    <br>
                                @endif
                                @if(isset($customer->mobile) && $customer->mobile != '')
                                   Phone: {{$customer->mobile ?? ''}}<br>
                                @endif
                                @if(isset($customer->telephone) && $customer->telephone != '')
                                    Telephone: {{$customer->telephone ?? ''}}
                                @endif
                            </address>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <h3><strong>INVOICE</strong></h3>
                            <p class="no-margin invoice-hedding">Invoice #: {{$invoice->invoice_code ?? ''}}</p>
                            <p class="">Invoice Date: {{$invoice->invoice_date ?? ''}}</p>
                        </div>
                    </div>
                    <!-- Row ends -->

                    <br/>

                    <!-- Row starts -->
                    <div class="row gutter">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>

                                        <th>Item</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit Price</th>
                                        <th class="text-right">Sub Total</th>
                                        <th class="text-right">Discount</th>
                                        <th class="text-right">Amount</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoiceproducts as $product)
                                        <tr class="text-body">


                                            <td>{{$product->product->item_code}}</td>
                                            <td class="text-center">{{$product->description}}</td>
                                            <td class="text-right">{{$product->qty}}</td>
                                            <td class="text-right">{{\App\Http\Helper::formatPrice($product->unit_price)}}</td>
                                            <td class="text-right">{{\App\Http\Helper::formatPrice($product->qty*$product->unit_price)}}</td>
                                            <td class="text-right">
                                                @if($product->discount!='' || $product->discount!=0)
                                                    @if($product->discount_type=='P')
                                                        {{$product->discount}}  %
                                                    @else
                                                        {{\App\Http\Helper::formatPrice($product->discount)}}
                                                    @endif
                                                @else --
                                                @endif
                                            </td>
                                            <td class="text-right">{{\App\Http\Helper::formatPrice($product->sub_total)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-sm-push-8 ">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody class="text-body2">
                                    <tr>
                                        <td class="total">Subtotal</td>

                                        <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->sub_total)}}</td>
                                    </tr>
                                    @if($invoice->discount!='' && $invoice->discount!=0)
                                        <tr>
                                            <td class="total">Discount</td>
                                            @if($invoice->discount_type=='P')
                                                <td class="text-right">
                                                    {{$invoice->discount}}%
                                                </td>
                                            @else
                                                <td class="text-right">
                                                    {{\App\Http\Helper::formatPrice($invoice->discount)}}
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if(!empty($invoice->vat_amount))
                                        <tr>
                                            <td class="total">VAT Amount (15%)</td>
                                            <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->vat_amount)}}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($invoice->nbt_amount))
                                        <tr>
                                            <td class="total">NBT Amount (2%)</td>
                                            <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->nbt_amount)}}</td>
                                        </tr>
                                    @endif
                                    <tr class="font-text-bold info">

                                        <td class="total">Total</td>
                                        <td class="text-right"> {{\App\Http\Helper::formatPrice($invoice->total)}}</td>
                                    </tr>
                                    <tr class="font-text-bold">
                                        <td class="total">Paid Amount</td>
                                        <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->paid_amount)}}</td>
                                    </tr>
                                    <tr class="font-text-bold">
                                        <td class="total"><strong>Amount Due</strong></td>
                                        <td class="text-right">
                                            <strong>{{\App\Http\Helper::formatPrice($invoice->balance)}}</strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-sm-pull-4">
                            <div class="text-danger text-justify">{!! $invoice->remarks!!}</div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="text-primary">Payment Method 1</h5>
                                    <img src="{{asset('images/card_payments.png')}}"
                                         id="customer_payment_methods_logo_sidebar"
                                         class="img-responsive">
                                </div>
                                <div class="col-sm-6">
                                    <h5 class="text-primary">Payment Method 2</h5>
                                    <p>Bank Transfer</p>

                                    <div class="row">
                                        <div class="col-xs-5"><p>Bank</p></div>
                                        <div class="col-xs-6"><p>Commercial Bank</p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5"><p>A/C Name</p></div>
                                        <div class="col-xs-6"><p>PRAMIX</p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5"><p>Branch</p></div>
                                        <div class="col-xs-6"><p>Ragama</p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5">A/C NO</div>
                                        <div class="col-xs-6">1000017692</div>
                                    </div>

                                </div>
                                <div class="col-sm-12">
                                    <br>
                                    <p>Cheque should be drawn to 'PRAMIX' 1000017692 (Commercial Bank)</p>
                                </div>
                            </div>


                        </div>



                    </div>
                    <!-- Row ends -->

                    <!-- Row starts -->
                    <div class="row gutter">

                    </div>
                    <!-- Row ends -->

                </div>
            </div>
            <div class="card card-default">

                <div class="card-body ">
                    <h4 class="text-primary"><strong>Billing Address</strong></h4>
                    <form id="billing_address_form">
                        <div id="delivery_address_panel">
                            <div class="row">
                                <div class="form-group col-sm-6">

                                    <label for="firstname">{{ __('xcustomer::customer.labels.first_name')}}</label>
                                    <input type="text" class="form-control validate[required]"
                                           id="billing_first_name"
                                           placeholder="Enter First Name"
                                           value="{{$customer->fname ?? ''}}"
                                           name="billing_first_name">

                                </div>
                                <div class="form-group col-sm-6">

                                    <label for="jobpositions">{{ __('xcustomer::customer.labels.last_name')}}</label>
                                    <input type="text" class="form-control validate[required]"
                                           id="billing_last_name"
                                           placeholder="Enter Last Name"
                                           value="{{$customer->lname ?? ''}}" name="billing_last_name">

                                </div>

                                <div class="form-group col-sm-6">

                                    <label for="jobpositions">{{ __('xcustomer::customer.labels.email')}}</label>
                                    <input type="text" class="form-control validate[required]" id="billing_email"
                                           placeholder="Enter E mail"
                                           value="{{$customer->email ?? ''}}"
                                           name="ship_email">

                                </div>

                                <div class="form-group col-sm-6">

                                    <label for="jobpositions">{{ __('xcustomer::customer.labels.mobile')}}</label>
                                    <br>
                                    <input type="tel" class="form-control validate[required]" id="billing_phone"
                                           placeholder="Enter Phone No"
                                           value="{{$customer->mobile ?? ''}}" name="ship_phone">

                                </div>
                                <div class="form-group col-sm-6">

                                    <label for="firstname">{{ __('xcustomer::customer.labels.street1')}}</label>
                                    <input type="text" class="form-control validate[required]"
                                           id="billing_addressline1"
                                           placeholder="Enter Address Line 1"
                                           value="{{$shipping_address->address_line_1 ?? ''}}"
                                           name="billing_addressline1">

                                </div>
                                <div class="form-group col-sm-6">

                                    <label for="jobpositions">{{ __('xcustomer::customer.labels.street2')}}</label>
                                    <input type="text" class="form-control validate[required]"
                                           id="billing_addressline2"
                                           placeholder="Enter Address Line 2"
                                           value="{{$shipping_address->address_line_1 ?? ''}}"
                                           name="billing_addressline2">


                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="jobpositions">City</label>

                                    <input type="text" class="form-control validate[required]"
                                           id="city"
                                           placeholder="Enter City"
                                           value="{{$shipping_address->address_line_1 ?? ''}}"
                                           name="city">

                                </div>


                                <div class="form-group col-sm-6">

                                    {{formDropdown(__('xcustomer::customer.labels.country'), 'business_country', $countryList , $business_address->country ?? '' , array('class' => 'form-control select2', 'id' => 'business_country'))}}


                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <!-- Row inside row starts -->

                <ul id="sidebar" class="nav nav-stacked affix">
                    <li><strong class="text-primary">Make payment with your bank card</strong></li>
                    <li><img src="{{asset('images/card_payments.png')}}" id="customer_payment_methods_logo_sidebar"
                             class="img-responsive"></li>

                    <li><a id="pay_now_btn" href="" class="btn btn-primary">Pay Now</a></li>

                    <li class="text-center" id="sidebar_or_label"><span class="label label-default" > <strong>OR</strong></span></li>
                    <li>
                        <p class="text-primary"><strong>Bank Transfer</strong></p>

                        <div class="row">
                            <div class="col-xs-5"><p><strong>Bank</strong></p></div>
                            <div class="col-xs-6"><p>Commercial Bank</p></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5"><p><strong>A/C Name</strong></p></div>
                            <div class="col-xs-6"><p>PRAMIX</p></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5"><p><strong>Branch</strong></p></div>
                            <div class="col-xs-6"><p>Ragama</p></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5"><p><strong>A/C NO</strong></p></div>
                            <div class="col-xs-6"><p>1000017692</p></div>
                        </div>
                    </li>
                    <li><h4><span class="label label-primary">Thank you for your business.</span></h4></li>
                </ul>


            <!-- Row inside row ends -->
        </div>
    </div>
    <!-- Row ends -->

@endsection

@section('include_js')
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            $("#pay_now_btn").click(function (e) {




                var valid = $("#billing_address_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                var button = $(this);


                var countrydata = $('#business_country').select2('data')

                var payment = {
                    "sandbox": false,
                    "merchant_id": "213751",       // Replace your Merchant ID
                    // "return_url": "http://www.pramixit.com",
                    // "cancel_url": "http://www.pramixit.com",
                    "notify_url": "http://www.pramixit.com",
                    "order_id": $('#invoice_id').val(),
                    "items": $('#invoice_code').val(),
                    "amount": $('#amount_due').val(),
                    "currency": "LKR",
                    "first_name": $('#billing_first_name').val(),
                    "last_name": $('#billing_last_name').val(),
                    "email": $('#billing_email').val(),
                    "phone": $('#billing_phone').val(),
                    "address": $('#billing_addressline1').val() +' '+ $('#billing_addressline2').val(),
                    "city": $('#city').val(),
                    "country": countrydata[0].text,

                };

                payhere.startPayment(payment);


                button.button('reset');

                return false;


            });




            // Called when user completed the payment. It can be a successful payment or failure
            payhere.onCompleted = function onCompleted(orderId) {
                console.log("Payment completed. OrderID:" + orderId);
                //Note: validate the payment and show success or failure page to the customer
            };

            // Called when user closes the payment without completing
            payhere.onDismissed = function onDismissed() {
                //Note: Prompt user to pay again or show an error page
                console.log("Payment dismissed");
            };

            // Called when error happens when initializing payment such as invalid parameters
            payhere.onError = function onError(error) {
                // Note: show an error page
                console.log("Error:"  + error);
            };

            // Put the payment variables here


            // Show the payhere.js popup, when "PayHere Pay" is clicked


        });
    </script>
@endsection


