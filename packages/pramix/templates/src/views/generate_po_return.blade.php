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

            <h3 class="text-primary"><strong><u>GRN RETURN</u></strong></h3>
        </div>
    </div>

        <div class="row ">
            <div class="col-xs-8">
                <address>
                    <h4>{{$supplier->fullname ?? ''}}</h4>
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
                    @if(isset($supplier->email) &&  $supplier->email!='')
                        <abbr title="email">{{ __('xinvoice::invoice.labels.email')}}:</abbr>
                        <a href="mailto:#" data-original-title="" title="">{{$supplier->email ?? ''}}</a><br>
                    @endif
                    @if(isset($supplier->mobile) && $supplier->mobile!='')
                        <abbr title="Phone">Phone:</abbr> {{$supplier->mobile ?? ''}}<br>
                    @endif
                    @if(isset($supplier->telephone) && $supplier->telephone!='')
                        <abbr title="Fax">Telephone:</abbr> {{$supplier->telephone ?? ''}}
                    @endif
                </address>
            </div>
            <div class="col-xs-4 ">
                <h4 class="heading-block">GRN Return # : {{$po_return->grn_return_code ?? ''}}</h4>
                <p class="text-info">Date : {{$po_return->grn_return_date ?? ''}}</p>

            </div>
        </div>
        <!-- Row ends -->

        <br/>

        <!-- Row starts -->
        <div class="table-responsive">
            <table class="table text-center">
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
                @foreach($po_return_products as $product)
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
        </div>

        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    @if(isset($po_return->remarks) &&  $po_return->remarks!='')
                        <div class="text-danger text-justify">{!! $po_return->remarks!!}</div>
                    @endif
                </td>
                <td style="width: 10%"></td>
                <td style="width: 40%">

                    <table style="width: 100%">
                        <tbody class="text-body2">
                        <tr>
                            <td class="total">Total</td>

                            <td class="text-right">{{\App\Http\Helper::formatPrice($po_return->sub_total)}}</td>
                        </tr>
                        @if($po_return->discount!='' || $po_return->discount!=0)
                            <tr>
                                <td class="total">Discount</td>
                                @if($po_return->discount_type=='P')
                                    <td class="text-right">
                                        {{$po_return->discount}}%
                                    </td>
                                @else
                                    <td class="text-right">
                                        {{\App\Http\Helper::formatPrice($po_return->discount)}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                        @if(isset($po_return->vat_amount) || $po_return->vat_amount!=0)
                            <tr>
                                <td class="total">VAT Amount (15%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($po_return->vat_amount)}}</td>
                            </tr>
                        @endif
                        @if(isset($po_return->nbt_amount) || $po_return->nbt_amount !=0)
                            <tr>
                                <td class="total">NBT Amount (2%)</td>
                                <td class="text-right">{{\App\Http\Helper::formatPrice($po_return->nbt_amount)}}</td>
                            </tr>
                        @endif
                        <tr class="font-text-bold ">

                            <td class="total">Sum Total</td>
                            <td class="text-right"> {{\App\Http\Helper::formatPrice($po_return->total)}}</td>
                        </tr>
                        <tr class="font-text-bold">
                            <td class="total">Paid Amount</td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($po_return->paid_amount)}}</td>
                        </tr>
                        <tr class="font-text-bold">
                            <td class="total"><strong>Amount Due</strong></td>
                            <td class="text-right">
                                <strong>{{\App\Http\Helper::formatPrice($po_return->balance)}}</strong></td>
                        </tr>
                        </tbody>
                    </table>

                </th>
            </tr>

        </table>

    </div>
</div>

</body>
</html>
