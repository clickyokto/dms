

<div class="main-container">
    <div class="card">
        <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-12">
                <h4 class="heading-block">Invoice - {{$invoice->invoice_code ?? ''}}</h4>
                <p class="text-info">{{$invoice->invoice_date ?? ''}}</p>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="text-right">
                    <a href="#">
                        <img src="{{public_path('/theme_assets/img/pramix_logo.png')}}" alt="Pramix Logo" class="logo"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <br/>
        <div class="row ">
            <div class="col-xs-7">
                <address>
                    <h4>{{ __('xinvoice::invoice.labels.company_name')}}</h4>
                    <abbr>{{ __('xinvoice::invoice.labels.company_address')}}</abbr><br>
                    <abbr title="email">{{ __('xinvoice::invoice.labels.email')}}:</abbr>
                    <a href="mailto:#" data-original-title=""
                       title="">{{ __('xinvoice::invoice.labels.company_p_mail')}}</a><br>
                    <abbr title="Phone">Phone:</abbr> {{ __('xinvoice::invoice.labels.company_phone')}}<br>
                    <abbr title="Fax">Telephone:</abbr> {{ __('xinvoice::invoice.labels.company_tel')}}
                </address>
            </div>
            <div class="col-xs-5">
                <address >
                    <h4>{{$customer->fullname ?? ''}}</h4>
                    @if(isset($business_address->address_line_1) && $business_address->address_line_1!='')
                    <br> {{$business_address->address_line_1 ?? ''}},
                    @endif
                    @if(isset($business_address->address_line_2) && $business_address->address_line_2!='')
                    <br> {{$business_address->address_line_2 ?? ''}},
                    @endif
                    @if(isset($business_address->city_name) && $business_address->city_name!='')
                    <br> {{$business_address->city_name ?? ''}},
                    @endif
                    @if(isset($business_address->district_name) && $business_address->district_name!='')
                    <br>{{$business_address->district_name ?? ''}}, </abbr> <br>
                    @endif
                    @if(isset($customer->email) &&  $customer->email!='')
                    <abbr title="email">{{ __('xinvoice::invoice.labels.email')}}:</abbr>
                    <a href="mailto:#" data-original-title="" title="">{{$customer->email ?? ''}}</a><br>
                    @endif
                    @if(isset($customer->mobile) && $customer->mobile!='')
                    <abbr title="Phone">Phone:</abbr> {{$customer->mobile ?? ''}}<br>
                    @endif
                    @if(isset($customer->telephone) && $customer->telephone!='')
                    <abbr title="Fax">Telephone:</abbr> {{$customer->telephone ?? ''}}
                    @endif
                </address>
            </div>
        </div>
        <!-- Row ends -->

        <br/>

        <!-- Row starts -->
        <div class="row ">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Sub Total</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoiceproducts as $product)
                        <tr class="text-body">
                            <td>{{$product->product->category->category_name}}</td>
                            <td>{{$product->product->item_code}}</td>
                            <td>{{$product->description}}</td>
                            <td>{{$product->qty}}</td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($product->unit_price)}}</td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($product->qty*$product->unit_price)}}</td>
                            <td>
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
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <div class="text-danger text-justify">{!! $invoice->remarks!!}</div>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody class="text-body2">
                            <tr>
                                <td class="total">Total</td>

                                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->sub_total)}}</td>
                            </tr>
                            @if($invoice->discount!='' || $invoice->discount!=0)
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
                            @if(isset($invoice->vat_amount) || $invoice->vat_amount!=0)
                            <tr>
                                <td class="total">VAT Amount (15%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->vat_amount)}}</td>
                            </tr>
                            @endif
                            @if(isset($invoice->nbt_amount) || $invoice->nbt_amount !=0)
                            <tr>
                                <td class="total">NBT Amount (2%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->nbt_amount)}}</td>
                            </tr>
                            @endif
                            <tr class="font-text-bold ">

                                <td class="total">Sum Total</td>
                                <td class="text-right"> {{\App\Http\Helper::formatPrice($invoice->total)}}</td>
                            </tr>
                            <tr class="font-text-bold">
                                <td class="total">Paid Amount</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice->paid_amount)}}</td>
                            </tr>
                            <tr class="font-text-bold">
                                <td class="total"><strong>Amount Due</strong></td>
                                <td class="text-right">
                                    <strong>{{\App\Http\Helper::formatPrice($invoice->balance)}}</strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- Row ends -->

        <!-- Row starts -->
        <div class="row ">

        </div>
        <!-- Row ends -->

    </div>
</div>
</div>
<!-- Row ends -->
</div>
</div>
