<table>
    <thead>
    <tr>
        <th>Customer Code / Business Name</th>
        <th>Title</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Mobile</th>
        <th>Telephone</th>
        <th>NIC</th>
        <th>Email</th>
        <th>Website</th>

    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->business_name }}</td>
            <td>{{ $customer->title }}</td>
            <td>{{ $customer->fname }}</td>
            <td>{{ $customer->lname }}</td>
            <td>{{ $customer->mobile }}</td>
            <td>{{ $customer->telephone }}</td>
            <td>{{ $customer->nic }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->website }}</td>
        </tr>
    @endforeach
    </tbody>
</table>