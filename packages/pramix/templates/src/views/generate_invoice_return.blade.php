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


                <h3 class="text-primary"><strong><u>Credit Note</u></strong></h3>

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
                    {{$business_address->address_line_2 ?? ''}},
                @endif
                @if(isset($business_address->city_name) && $business_address->city_name!='')
                    <br> {{$business_address->city_name ?? ''}},
                @endif
                @if(isset($business_address->district_name) && $business_address->district_name!='')
                    {{$business_address->district_name ?? ''}}, </abbr> <br>
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


            <h4 class="heading-block">Invoice return # : {{$invoice_return->invoice_return_code ?? ''}}</h4>
            <p class="text-info">Date : {{$invoice_return->invoice_return_date ?? ''}}</p>

        </div>
    </div>
    <!-- Row ends -->


        <table class="table text-center" width="100%">
            <thead>
            <tr>

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
            @foreach($invoice_return_products  as $product)
                <tr class="text-body">

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

    <table style="width: 100% ; margin-top: 40px">
        <tr>
            <td style="width: 50%">
                @if(isset($invoice_return->remarks) &&  $invoice_return->remarks!='')
                    <div class="text-justify">{!! $invoice_return->remarks!!}</div>
                @endif
            </td>
            <td style="width: 10%"></td>
            <td style="width: 40%">

    <table style="width: 100%">
        <tbody class="text-body2">
        <tr>
            <td class="total">Sub Total</td>

            <td class="text-right">{{\App\Http\Helper::formatPrice($invoice_return->sub_total)}}</td>
        </tr>
        @if($invoice_return->discount!='' || $invoice_return->discount!=0)
            <tr>
                <td class="total">Discount</td>
                @if($invoice_return->discount_type=='P')
                    <td class="text-right">
                        {{$invoice_return->discount}}%
                    </td>
                @else
                    <td class="text-right">
                        {{\App\Http\Helper::formatPrice($invoice_return->discount)}}
                    </td>
                @endif
            </tr>
        @endif
        @if(isset($invoice_return->vat_amount) && $invoice_return->vat_amount!=0)
            <tr>
                <td class="total">VAT Amount (15%)</td>
                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice_return->vat_amount)}}</td>
            </tr>
        @endif
        @if(isset($invoice_return->nbt_amount) && $invoice_return->nbt_amount !=0)
            <tr>
                <td class="total">NBT Amount (2%)</td>
                <td class="text-right">{{\App\Http\Helper::formatPrice($invoice_return->nbt_amount)}}</td>
            </tr>
        @endif
        <tr class="font-text-bold ">

            <td class="total">Total Amount Payable</td>
            <td class="text-right"> {{\App\Http\Helper::formatPrice($invoice_return->total)}}</td>
        </tr>
        <tr class="font-text-bold">
            <td class="total">Refund</td>
            <td class="text-right">{{\App\Http\Helper::formatPrice($invoice_return->refund)}}</td>
        </tr>
        <tr class="font-text-bold">
            <td class="total"><strong>Credit for the customer</strong></td>
            <td class="text-right">
                <strong>{{\App\Http\Helper::formatPrice($invoice_return->customer_credit)}}</strong></td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
