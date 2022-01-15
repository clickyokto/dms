<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>


        body {
            font-size: 12px;
            font-family: DejaVu Sans;
        }


        .logo {
            margin-right: 30px;
            margin-bottom: 25px;
            width: 150px;
        }

    </style>

</head>
<body>

<div class="main-container">
    <div class="card">
        <div class="text-center">
            {{--{{getLogo()}}--}}
            <h4><strong>{{getConfigArrayValueByKey('COMPANY_DETAILS','company_name')}}</strong></h4>
            <p>{{getConfigArrayValueByKey('COMPANY_DETAILS','street1')}} {{getConfigArrayValueByKey('COMPANY_DETAILS','street2')}}
                , {{getConfigArrayValueByKey('COMPANY_DETAILS','city')}}. Telephone
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','telephone')}} | Fax
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','fax')}}</p>

            <p>Web : {{getConfigArrayValueByKey('COMPANY_DETAILS','website')}} | Email
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','email')}}</p>


                <h3 class="text-primary"><strong><u>QUOTATION</u></strong></h3>

        </div>
    </div>
    <div class="row ">
        <div class="col-xs-8">
            <address>
                <strong>{{$customer->fullname ?? ''}}</strong>
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
        <div class="col-xs-4 ">

            <h4 class="heading-block">Quotation # : {{$quotation->quotation_code ?? ''}}</h4>
            <p class="text-info">Date : {{$quotation->quotation_date ?? ''}}</p>

        </div>
    </div>
    <!-- Row ends -->



            <div class="">
                <table class="table-striped text-center" style="width: 100%">
                    <thead>
                    <tr>

                        <th class="text-center" style="width: 20%">Item</th>
                        <th class="text-center" style="width: 20%">Description</th>
                        <th class="text-center" style="width: 8%">Quantity</th>
                        <th class="text-center" style="width: 13%">Unit Price</th>
                        <th class="text-center" style="width: 13%">Sub Total</th>
                        <th class="text-center" style="width: 13%">Discount</th>
                        <th class="text-center" style="width: 13%">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quotationProducts as $product)
                        <tr class="text-body">

                            <td>{{$product->product->item_code}}</td>
                            <td class="text-left">{{$product->description}}</td>
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

        <table style="width: 100% ; margin-top: 40px">
            <tr>
                <td style="width: 50%">
                    @if(isset($quotation->remarks) &&  $quotation->remarks!='')
                        <div class="text-justify">{!! $quotation->remarks!!}</div>
                    @endif
                </td>
                <td style="width: 10%"></td>
                <td style="width: 40%">

                    <table style="width: 100%">
                        <tbody class="text-body2">
                        <tr>
                            <td class="total">Sub Total</td>

                            <td class="text-right">{{\App\Http\Helper::formatPrice($quotation->sub_total)}}</td>
                        </tr>
                        @if($quotation->discount!='' || $quotation->discount!=0)
                            <tr>
                                <td class="total">Discount</td>
                                @if($quotation->discount_type=='P')
                                    <td class="text-right">
                                        {{$quotation->discount}}%
                                    </td>
                                @else
                                    <td class="text-right">
                                        {{\App\Http\Helper::formatPrice($quotation->discount)}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                        @if(isset($quotation->vat_amount) && $quotation->vat_amount!=0)
                            <tr>
                                <td class="total">VAT Amount (15%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($quotation->vat_amount)}}</td>
                            </tr>
                        @endif
                        @if(isset($quotation->nbt_amount) && $quotation->nbt_amount !=0)
                            <tr>
                                <td class="total">NBT Amount (2%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($quotation->nbt_amount)}}</td>
                            </tr>
                        @endif
                        <tr class="font-text-bold ">

                            <td class="total">Total Amount Payable</td>
                            <td class="text-right"> {{\App\Http\Helper::formatPrice($quotation->total)}}</td>
                        </tr>
                        <tr class="font-text-bold">
                            <td class="total">Paid Amount</td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($quotation->paid_amount)}}</td>
                        </tr>
                        <tr class="font-text-bold">
                            <td class="total"><strong>Amount Due</strong></td>
                            <td class="text-right">
                                <strong>{{\App\Http\Helper::formatPrice($quotation->balance)}}</strong></td>
                        </tr>
                        </tbody>
                    </table>

                </th>
            </tr>

        </table>


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
</body>
</html>
