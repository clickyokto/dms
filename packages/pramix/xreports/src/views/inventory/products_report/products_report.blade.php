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
<H2>Products Report</H2>

<p>Filtered by:</p>
<p>Product Category : @if($category_details == NULL) All
    Categories @else {{$category_details->category_name}} @endif</p>
<p>Product Type : @if($product_type == NULL) All Products @else  @if($product_type == 'stock')
        {{__('xproduct::product.labels.stock')}}
    @elseif ($product_type == 'service')
        {{__('xproduct::product.labels.service')}}
    @elseif ($product_type == 'non_stock')
        {{__('xproduct::product.labels.non_stock')}}
    @endif
    @endif</p>


<table>
    <tr>
        <th>Item Name/Code</th>
        <th>Type</th>
        <th>Category</th>
        <th>Quantity on Hand</th>
        <th>Reorder Point</th>
        <th>Average Cost</th>
        <th>Selling Price</th>

    </tr>


    @foreach($products as $product)

        <tr>

            <td>{{$product->item_code}}</td>
            <td>
                @if($product->type == 'stock')
                    {{__('xproduct::product.labels.stock')}}
                @elseif ($product->type == 'service')
                    {{__('xproduct::product.labels.service')}}
                @elseif ($product->type == 'non_stock')
                    {{__('xproduct::product.labels.non_stock')}}
                @endif
            </td>
            <td>{{$product->category->category_name}}</td>
            <td>{{$product->qty_on_hand}}</td>
            <td>{{$product->reorder_poiing}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice(\Pramix\XInventory\Models\AverageCostModel::getCost($product->id))}}</td>
            <td style="text-align: right">{{\App\Http\Helper::formatPrice($product->price)}}</td>

        </tr>



    @endforeach


</table>
</body>
</html>
