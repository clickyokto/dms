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
<H2>{{__('xreports::reports.headings.inventory_movement_summary_report')}}</H2>

<p>Filtered by:</p>
<p>Product : @if($product_details == NULL) All Products @else {{$product_details->item_code}} @endif</p>
<p>Product Category :  @if($category_details == NULL) All Categories @else {{$category_details->category_name}} @endif</p>
<p>From Date : {{$from_date ?? ''}}</p>
<p>To Date : {{$end_date ?? ''}}</p>

<table>
    <tr>

        <th>Product</th>
        <th>Date</th>

        <th>Qty Change</th>
        <th>Before Qty</th>
        <th>After Qty</th>
        {{--<th>Stock Status</th>--}}
        <th>Note</th>
        <th>Customer</th>
    </tr>


    <?php $id = 1;?>

    @foreach($inventory_records as $inventory_record)

        <tr>

            <td>{{$inventory_record->product->item_code}}</td>
            <td>{{$inventory_record->created_at}}</td>

            <td>@if($inventory_record->type == 'I') +{{$inventory_record->qty}} @else -{{$inventory_record->qty}} @endif</td>
            <td>{{$inventory_record->qty_before}}</td>
            <td>{{$inventory_record->qty_after}}</td>
            {{--<td>@if($inventory_record->type == 'I') In Stock @else Out Stock @endif</td>--}}
            <td>
                {{ucwords(str_replace('_',' ',getConfigArrayKeyByValue('STOCK_TRANSACTION_TYPES',$inventory_record->transaction_type_id)) )}}

            </td>
            <td>

                @if(getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'sales_order') == $inventory_record->transaction_type_id)
                    @php $invoice = \Pramix\XInvoice\Models\InvoiceModel::with('customer')->where('invoice_code',$inventory_record->order_number )->first();

                    @endphp
                    {{$invoice->customer->company_name ?? ''}}
                @endif

            </td>
            {{--<td>{{$inventory_record->user->username}}</td>--}}
        </tr>

        <?php $id++;?>

    @endforeach


</table>
</body>
</html>
