<table>
    <thead>
    <tr>
        <th>customer_code</th>
        <th>full_name</th>
        <th>contact_no</th>
        <th>contact_no_02</th>
        <th>email</th>
        <th>address_line_1</th>
        <th>address_line_2</th>
        <th>district</th>
        @foreach($products as $product)
            <th>{{$product->code}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->code }}</td>
            <td>{{ $customer->full_name }}</td>
            <td>{{ $customer->contact_no }}</td>
            <td>{{ $customer->contact_no_02 }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->address_1 }}</td>
            <td>{{ $customer->address_2 }}</td>
            <td>{{ $customer->district->name_en ?? '' }}</td>
            @foreach($products as $product)
                <?php $customer_product_supplier = \App\Models\ClientProductSuppliersModel::where('client_id',$customer->id)->where('product_id', $product->id)->with('supplier')->first(); ?>
                <th>{{$customer_product_supplier->supplier->code ?? ''}}</th>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>