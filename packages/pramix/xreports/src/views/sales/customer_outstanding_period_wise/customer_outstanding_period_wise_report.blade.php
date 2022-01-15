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
        hr{
            margin: 3px;
        }

    </style>

</head>
<body>

<div class="main-container">
    <p>C.M.K. Enterprises</p>




        <table class="table" style="width: 100%">
            <tr>
                <th>Debtor Name</th>
                <th class="text-right">Total</th>
                <th class="text-right">0 - 30 Days</th>
                <th class="text-right">31 - 60 Days</th>
                <th class="text-right">61 - 90 Days</th>
                <th class="text-right">Over 91 Days</th>
            </tr>
        @foreach($customers as $customer)



                <tr>
                    <td style="width: 25%">{{$customer->company_name}} {{$customer->mobile}}</td>
                    <td class="text-right" style="width: 15%">{{\App\Http\Helper::formatNumber($customer['invoice_total'])}}</td>
                    <td class="text-right" style="width: 15%">{{\App\Http\Helper::formatNumber($customer['less_30_days'])}}</td>
                    <td class="text-right" style="width: 15%">{{\App\Http\Helper::formatNumber($customer['between_31_60_days'])}}</td>
                    <td class="text-right" style="width: 15%">{{\App\Http\Helper::formatNumber($customer['between_61_90_days'])}}</td>
                    <td  class="text-right" style="width: 15%">{{\App\Http\Helper::formatNumber($customer['over_91'])}}</td>
                </tr>

            @endforeach


        </table>





</div>

</body>
</html>
