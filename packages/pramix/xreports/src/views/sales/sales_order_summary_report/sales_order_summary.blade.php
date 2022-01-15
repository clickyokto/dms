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
{!!getLogo()!!}
<H2>Sales Order Summary Report</H2>

<p>Filtered by:</p>
<p>Payment Status : {{$payment_status ?? ''}}</p>
<p>From Date : {{$from_date ?? ''}}</p>
<p>To Date : {{$end_date ?? ''}}</p>


<table>
    <tr>
        <th>Order #</th>
        <th>Customer Code</th>
        <th>Customer Name</th>
        <th>Payment Status</th>
        <th>Date</th>
        <th>Subtotal</th>
        <th>VAT</th>
        <th>NBT</th>
        <th>Discount</th>
        <th>Total</th>
        <th>Paid</th>
        <th>Balance</th>


    </tr>
    <?php
    $total_vat = 0;
    $total_nbt = 0;
    $total_discount = 0;
    $total_total = 0;
    $total_paid = 0;
    $total_balance = 0;
    $total_subtotal = 0;
    ?>

    @foreach($invoices as $invoice)

        <tr>
            <?php

            $discount = 0;

            if ($discount != 0){

                if ($invoice->discount_type == 'P')
                    $discount = $invoice->sub_total * (100 - $invoice->discount) / 100;

                else
                    $discount = $invoice->sub_total - $invoice->discount;
         }

            $total_vat += $invoice->vat_amount;
            $total_nbt += $invoice->nbt_amount;
            $total_discount += $discount;
            $total_total += $invoice->total;
            $total_paid += $invoice->paid_amount;
            $total_balance += $invoice->balance;
            $total_subtotal += $invoice->sub_total;
            ?>


            <td>{{$invoice->invoice_code}}</td>
            <td>{{$invoice->customer->business_name}}</td>
            <td>{{$invoice->customer->full_name}}</td>
            <td>
                @if ($invoice->total == $invoice->paid)
                    Completed
                @elseif ($invoice->total == $invoice->balance)
                    Pending
                @elseif ($invoice->total != $invoice->balance)
                    Partial
                @endif
            </td>
                <td>{{$invoice->invoice_date}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->sub_total)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->vat_amount)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->nbt_amount)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($discount)}}          </td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->total)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->paid_amount)}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($invoice->balance)}}</td>
        </tr>



    @endforeach
    <tr>
        <th></th>
<th></th>
        <th></th>
        <th></th>
        <th>Total</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_subtotal)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_vat)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_nbt)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_discount)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_total)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_paid)}}</th>
        <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_balance)}}</th>
    </tr>


</table>
</body>
</html>
