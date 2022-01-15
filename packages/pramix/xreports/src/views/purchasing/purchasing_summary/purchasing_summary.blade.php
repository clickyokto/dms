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
{!! getLogo()!!}
<H2>Purchase Order Summary Report</H2>

<p>Filtered by:</p>
<p>Payment Status : {{$payment_status ?? ''}}</p>
<p>From Date : {{$from_date ?? ''}}</p>
<p>To Date : {{$end_date ?? ''}}</p>


<table>
    <tr>
        <th>Order #</th>
        <th>Supplier Code</th>
        <th>Supplier Name</th>
        <th>Payment Status</th>
        <th>Date</th>
        <th>Subtotal</th>
        <th>Discount</th>
        <th>Total</th>
        <th>Paid</th>
        <th>Balance</th>



    </tr>
    <?php
    $total_discount = 0;
    $total_total = 0;
    $total_paid = 0;
    $total_balance = 0;
    $total_subtotal = 0;
    ?>

    @foreach($purchase_orders as $purchase_order)

        <tr>
            <?php

            $discount = 0;

            if ($discount != 0){

                if ($purchase_order->discount_type == 'P')
                    $discount = $purchase_order->sub_total * (100 - $purchase_order->discount) / 100;

                else
                    $discount = $purchase_order->sub_total - $purchase_order->discount;
            }

            $total_discount += $discount;
            $total_total += $purchase_order->total;
            $total_paid += $purchase_order->paid_amount;
            $total_balance += $purchase_order->balance;
            $total_subtotal += $purchase_order->sub_total;
            ?>


            <td>{{$purchase_order->purchase_order_code}}</td>
            <td>{{$purchase_order->supplier->business_name}}</td>
            <td>{{$purchase_order->supplier->full_name}}</td>
            <td>
                @if ($purchase_order->total == $purchase_order->paid)
                    Completed
                @elseif ($purchase_order->total == $purchase_order->balance)
                    Pending
                @elseif ($purchase_order->total != $purchase_order->balance)
                    Partial
                @endif
            </td>
                <td>{{$purchase_order->purchase_order_date}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($purchase_order->sub_total)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($discount)}}          </td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($purchase_order->total)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($purchase_order->paid_amount)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($purchase_order->balance)}}</td>
        </tr>



    @endforeach
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>Total</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_subtotal)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_discount)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_total)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_paid)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_balance)}}</th>
    </tr>


</table>
</body>
</html>
