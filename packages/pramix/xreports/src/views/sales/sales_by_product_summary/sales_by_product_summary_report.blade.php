<!DOCTYPE html>
<html lang="en">
<head>
    <style>

        body {
            font-size: 12px;
            font-family: DejaVu Sans;
        }

        table {

            border-collapse: collapse;
            width: 100%;
            padding-bottom: 10px;
            padding-top: 0px;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            /*    padding-top: 12px;
                padding-bottom: 12px;*/
            text-align: left;
            background-color: #f4f9f3;
            color: black;
        }

        .table_title {
            margin-bottom: 0px;
            padding-bottom: 5px;
        }

        .logo {
            margin-right: 30px;
            margin-bottom: 25px;
        }


    </style>

</head>
<body>
{!! getLogo() !!}
<H2>Sales by Product Summary Report</H2>

<p>Filtered by:</p>
<p>Product : @if($product_details == NULL) All Products @else {{$product_details->item_code}} @endif</p>
<p>Product Category :  @if($category_details == NULL) All Categories @else {{$category_details->category_name}} @endif</p>
<p>From Date : {{$from_date ?? ''}}</p>
<p>To Date : {{$end_date ?? ''}}</p>



<table>
    <tr>
        <th>Item Name/Code</th>
        <th>Sold Quantity</th>
        <th>Total Sales</th>



    </tr>
    <?php
    $total_qty = 0;
    $total_sub_total = 0;

    ?>

    @foreach($invoice_products as $invoice_product)

        <tr>
            <?php
            $total_qty += $qty_sum[$invoice_product->product_id];
            $total_sub_total += $sub_total_sum[$invoice_product->product_id];
            ?>


            <td>{{$invoice_product->product->item_code}}</td>
            <td style="text-align: right">{{$qty_sum[$invoice_product->product_id]}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($sub_total_sum[$invoice_product->product_id])}}</td>
        </tr>



    @endforeach
    <tr>
        <th>Total</th>
        <th style="text-align: right">{{$total_qty}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_sub_total)}}</th>

    </tr>


</table>
</body>
</html>