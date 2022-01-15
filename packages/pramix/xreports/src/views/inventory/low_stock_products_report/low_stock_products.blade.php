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
<H2>Low Stock Products Report</H2>


<p>Date : {{\Carbon\Carbon::now()}}</p>

<table>
    <tr>
        <th>Category</th>
        <th>Stock ID</th>
        <th>Item Name/Code</th>
        <th>Qty on hand</th>
    </tr>



    @foreach($products as $product)

        <tr>

            <td>{{$product->category->category_name}}</td>
            <td>{{$product->stock_id}}</td>
            <td>{{$product->item_code}}</td>
           <td>{{\Pramix\XInventory\Models\Inventory::getProductStock($product->id)}}</td>


        </tr>


    @endforeach


</table>
</body>
</html>
