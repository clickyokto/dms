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
    <div class="row">
        <div class="col-xs-6">
            <p>C.M.K. Enterprises</p>
            <p>Cheque Return Statement</p>
        </div>
        <div class="col-xs-6">
            <p >{{Carbon\Carbon::now()}}</p>
        </div>
    </div>



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
            $total_debit_amount  = 0;
            $total__credit_amount = 0;
              $total__balance_amount = 0;
        @endphp
        <table style="width: 100%">

            <tr>

                <td style="width: 12%">Invoice #</td>
                <td style="width: 12%">Date</td>
                <td style="width: 20%">Description</td>
                <td style="width: 14%; text-align: right;">Debit</td>
                <td style="width: 14%; text-align: right;">Credit</td>
                <td style="width: 14%; text-align: right;">Balance</td>
            </tr>
            @foreach($customer->activeOutstandingInvoices as $invoice)
                @php $payment_count = count($invoice->invoicePayment);
                $i = 0;
                @endphp

                @foreach($invoice->invoicePayment as $payment)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td style="width: 12%">{{$invoice->invoice_code}}</td>
                        <td style="width: 12%">{{$invoice->invoice_date}}</td>
                        <td style="width: 20%">{{$payment->payment_remarks}}</td>
                        <td style="width: 14%; text-align: right;">@if($payment->cheque_id == NULL) {{\App\Http\Helper::formatNumber($payment->payment_amount)}} @endif</td>
                        <td style="width: 14%; text-align: right;">@if($payment->cheque_id != NULL) {{\App\Http\Helper::formatNumber($payment->payment_amount)}} @endif</td>
                        <td style="width: 14%; text-align: right;">@if($i == $payment_count){{\App\Http\Helper::formatNumber($invoice->balance)}} @endif</td>
                    </tr>

                    @php
                                         if($payment->cheque_id == NULL)
                                                $total_debit_amount  += $payment->payment_amount;

                                        if($payment->cheque_id != NULL)
                                          $total__credit_amount += $payment->payment_amount;

                                          if($i == $payment_count)
                                            $total__balance_amount += $invoice->balance;
                    @endphp
                @endforeach


            @endforeach


            <tr>

                <td style="width: 12%">Total</td>
                <td style="width: 12%"></td>
                <td style="width: 20%"></td>
                <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total_debit_amount)}}</td>
                <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total__credit_amount)}}</td>
                <td style="width: 14%; text-align: right;">{{\App\Http\Helper::formatPrice($total__balance_amount)}}</td>
            </tr>
        </table>
        <hr class="double_hr">
    @endforeach


</div>

</body>
</html>
