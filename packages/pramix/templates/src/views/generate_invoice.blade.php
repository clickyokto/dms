<!DOCTYPE html>
<html lang="en">
<head>

     <style>


        body {
            font-size: 12px;
            font-family: DejaVu Sans;
            padding-top: 50px;
            text-transform: uppercase;
        }


    </style>

</head>
<body>

    <table style="width: 100%">
        <tr>
            <td style="width: 70%">
                <table style="width: 80%">
                    <tbody>
                    <tr>
                        <td style="width: 20%">
                            @if(isset($customer))
                            <abbr title="code">Dealer :</abbr></td>
@endif
                        <td style="width: 80%">

                                @if(isset($customer->fullname) && $customer->fullname != '')
                                    {{$customer->fullname ?? ''}}<br>
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%"><abbr title="code"> </abbr></td>
                        <td style="width: 80%">

                                @if(isset($business_address->address_line_1) && $business_address->address_line_1 != '')
                                    {{$business_address->address_line_1 ?? ''}},
                                @endif
                                @if(isset($business_address->address_line_2) && $business_address->address_line_2 != '')
                                    {{$business_address->address_line_2 ?? ''}}, <br>
                                @endif
                                @if(isset($business_address->city_name) && $business_address->city_name != '')
                                    {{$business_address->city_name ?? ''}}.
                                @endif

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 30%">
                <table style="width: 100%">
                    <tbody>
                    <tr >
                        <td style="width: 40%">Invoice #</td>
                        <td> :</td>
                        <td >{{$invoice->invoice_code ?? ''}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Date</td>
                        <td> :</td>
                        <td >{{$invoice->invoice_date ?? ''}}</td>
                    </tr>
                    @if(isset($invoice->rep))
                    <tr>
                        <td style="width: 40%">Rep.</td>
                        <td> :</td>
                        <td >{{$invoice->rep->username ?? ''}}</td>
                    </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>





        <table  style="width: 100%; margin-top: 20px;">
            <thead>
            <tr>

                <th class="text-center" >Product</th>
                <th class="text-center" >Part Number</th>
                <th class="text-center" >Description</th>
                <th class="text-center" >Qty</th>
                <th class="text-right" >Unit Price</th>

                <th class="text-right" >Sub Total</th>
                <th class="text-center" >Discount</th>

                <th class="text-right" >Net Value</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceproducts as $product)
                <tr class="text-body">
                    <td>{{$product->product->stock_id}}</td>
                    <td>{{$product->product->item_code}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->qty}}</td>
                    <td style="text-align: right;">{{\App\Http\Helper::formatPrice($product->unit_price)}}</td>

                    <td style="text-align: right;">{{\App\Http\Helper::formatPrice($product->qty*$product->unit_price)}}</td>
                    <td style="text-align: right;">
                        @if($product->discount!='' && $product->discount!=0)
                            @if($product->discount_type=='P')
                                {{$product->discount}}  %
                            @else
                                {{\App\Http\Helper::formatPrice($product->discount)}}
                            @endif
                        @else 0.00
                        @endif
                    </td>

                    <td style="text-align: right;">{{\App\Http\Helper::formatPrice($product->sub_total)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


    <table style="width: 100% ; margin-top: 40px">
        <tr>
            <td style="width: 50%">
                @if(isset($invoice->remarks) &&  $invoice->remarks!='')
                    <div class="text-justify">{!! $invoice->remarks!!}</div>
                @endif
            </td>
            <td style="width: 10%"></td>
            <td style="width: 40%">

                <table style="width: 100%">
                    <tbody class="text-body2">
                    <tr>
                        <td class="total">Gross Amount</td>

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

                    <tr class="font-text-bold ">

                        <td class="total">Total Amount</td>
                        <td class="text-right"> {{\App\Http\Helper::formatPrice($invoice->total)}}</td>
                    </tr>

                    </tbody>
                </table>

            </th>
        </tr>

    </table>



</body>
</html>
