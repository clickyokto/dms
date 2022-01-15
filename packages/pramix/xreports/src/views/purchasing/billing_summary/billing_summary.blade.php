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
<H2>Billing Summary Report</H2>

<p>Filtered by:</p>
<p>Payment Method : @if($payment_method == NULL) All Payments @else {{$payment_method}} @endif</p>
<p>Supplier : @if($supplier_details == NULL) All Suppliers @else {{$supplier_details->business_name}} @endif</p>
<p>From Date : {{$from_date ?? ''}}</p>
<p>To Date : {{$end_date ?? ''}}</p>


<table>
    <tr>
        <th>Bill #</th>
        <th>PO #</th>
        <th>Date</th>
        <th>Ref #</th>
        <th>Remarks</th>
        <th>Amount</th>
    </tr>
    <?php
    $total_amount = 0;
    ?>

    @foreach($purchase_order_payments as $purchase_order_payment)

        <tr>
            <?php
            $total_amount += $purchase_order_payment->payment_amount;
            ?>

            <td>{{$purchase_order_payment->payment_code}}</td>
            <td>{{$purchase_order_payment->purchase_order->purchase_order_code}}</td>
                <td>{{$purchase_order_payment->cheque_date}}</td>
                <td>{{$purchase_order_payment->payment_ref_no}}</td>
                <td>{{$purchase_order_payment->payment_remarks}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($purchase_order_payment->payment_amount)}}</td>
        </tr>



    @endforeach
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total</th>
            <th style="text-align: right">{{\App\Http\Helper::formatPrice($total_amount)}}</th>

        </tr>


</table>
</body>
</html>
