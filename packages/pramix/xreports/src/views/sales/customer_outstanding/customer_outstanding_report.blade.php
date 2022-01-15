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

        .double_hr {
            overflow: visible; /* For IE */
            padding: 0;
            border: none;
            border-top: medium double #333;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        hr {
            margin: 3px;
        }

    </style>

</head>
<body>

<div class="main-container">
    <p>C.M.K. Enterprises</p>
    <table style="width: 100%">
        <tr>
            <th style="width: 12%">Invoice #</th>
            <th style="width: 12%">Date</th>
            <th style="width: 10%">Description</th>
            <th style="width: 14%; text-align: right;">Debit</th>
            <th style="width: 14%; text-align: right;">Credit</th>
            <th style="width: 14%; text-align: right;">Return</th>
            <th style="width: 14%; text-align: right;">Balance</th>
        </tr>
    </table>
    @foreach($customers as $customer)
        <strong>Debator :</strong> {{$customer->company_name ?? ''}} | <strong>Rep
            :</strong> {{$customer->rep->fname ?? ''}} {{$customer->rep->lname ?? ''}} <br>

        @if(isset($business_address->customerAddress->address_line_1) && $business_address->customerAddress->address_line_1 != '')
            {{$business_address->customerAddress->address_line_1 ?? ''}},
        @endif
        @if(isset($business_address->customerAddress->address_line_2) && $business_address->customerAddress->address_line_2 != '')
            {{$business_address->customerAddress->address_line_2 ?? ''}},
        @endif
        @if(isset($business_address->customerAddress->city_name) && $business_address->customerAddress->city_name != '')
            {{$business_address->customerAddress->city_name ?? ''}}.<br>
        @endif
        @if(isset($customer->mobile) && $customer->mobile != '')
            {{$customer->mobile ?? ''}}
        @endif
        <hr>
        @php
            $total_invoice_amount  = 0;
            $total__paid_amount = 0;
              $total__return_amount = 0;
            $total_balance_total = 0;
        @endphp
        <table style="width: 100%">

            @foreach($customer->activeOutstandingInvoices as $invoice)



                <tr>

                    <td style="width: 12%">{{$invoice->invoice_code}}</td>
                    <td style="width: 12%">
                        @php $count_days = \Carbon\Carbon::parse($invoice->invoice_date)->diffInDays(\Carbon\Carbon::now(), false); @endphp



                        {{$invoice->invoice_date}} ({{$count_days}})</td>
                    <td style="width: 20%"></td>
                    <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($invoice->total)}}</td>
                    <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($invoice->paid_amount)}}</td>

                    <td style="width: 14%; text-align: right;"> {{\App\Http\Helper::formatPrice($invoice->returned_amount)}}</td>
                    <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($invoice->balance)}}</td>

                </tr>
                @php
                    $total_invoice_amount  += $invoice->total;
                    $total__paid_amount += $invoice->paid_amount;


                      $total__return_amount += $invoice->returned_amount ;
                    $total_balance_total += $invoice->balance;
                @endphp
            @endforeach

            <tr>

                <th style="width: 12%">Total</th>
                <th style="width: 12%"></th>
                <th style="width: 20%"></th>
                <th style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total_invoice_amount)}}</th>
                <th style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total__paid_amount)}}</th>
                <th style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total__return_amount)}}</th>
                <th style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total_balance_total)}}</th>
            </tr>
        </table>
        <hr class="double_hr">
    @endforeach


</div>

</body>
</html>
