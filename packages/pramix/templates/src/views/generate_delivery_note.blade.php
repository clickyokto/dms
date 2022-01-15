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


        .logo {
            margin-right: 30px;
            margin-bottom: 25px;
            width: 150px;
        }

    </style>

</head>
<body>

<div class="main-container">
    <div class="card">
        <div class="text-center">
            {{--{{getLogo()}}--}}
            <h4><strong>{{getConfigArrayValueByKey('COMPANY_DETAILS','company_name')}}</strong></h4>
            <p>{{getConfigArrayValueByKey('COMPANY_DETAILS','street1')}} {{getConfigArrayValueByKey('COMPANY_DETAILS','street2')}}
                , {{getConfigArrayValueByKey('COMPANY_DETAILS','city')}}. Telephone
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','telephone')}} | Fax
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','fax')}}</p>

            <p>Web : {{getConfigArrayValueByKey('COMPANY_DETAILS','website')}} | Email
                : {{getConfigArrayValueByKey('COMPANY_DETAILS','email')}}</p>

                <h3 class="text-primary"><strong><u>DELIVERY NOTE</u></strong></h3>

        </div>
    </div>

    <div class="row ">

        <div class="col-xs-8">
            <address>
                <table class="table-striped text-center">
                    <tbody>
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="code">{{ __('xcustomer::customer.labels.business_name')}}: </abbr></td>
                        <td class="text-left"><strong>{{$customer->business_name ?? ''}}</strong></td>
                    </tr>
                    @if($customer->customer_type=='C')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="name">{{ __('xcustomer::customer.labels.customer_name')}}:</abbr></td>
                            <td class="text-left"><strong>{{$customer->fullname ?? ''}}</strong></td>
                        </tr>
                    @else
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="name">{{ __('xcustomer::customer.labels.company_name')}}:</abbr></td>
                            <td class="text-left"><strong>{{$customer->company_name ?? ''}}</strong></td>
                        </tr>
                    @endif
                    @if(isset($business_address->address_line_1) && $business_address->address_line_1!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="name">{{ __('xcustomer::customer.labels.customer_address')}}:</abbr></td>
                            <td class="text-left">{{$business_address->address_line_1 ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($business_address->address_line_2) && $business_address->address_line_2!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"></td>
                            <td class="text-left">{{$business_address->address_line_2 ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($business_address->city_name) && $business_address->city_name!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"></td>
                            <td class="text-left">{{$business_address->city_name ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($business_address->district_name) && $business_address->district_name!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"></td>
                            <td class="text-left">{{$business_address->district_name ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($customer->company_branch))
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="branch">{{ __('xcustomer::customer.labels.company_branch')}}:</abbr></td>
                            <td class="text-left"><strong>{{$customer->company_branch ?? ''}}</strong></td>
                        </tr>
                    @endif
                    @if($customer->customer_type=='B')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="branch">{{ __('xcustomer::customer.labels.att')}}:</abbr></td>
                            <td class="text-left"><strong>{{$customer->fullname ?? ''}}</strong></td>
                        </tr>
                    @endif
                    @if(isset($customer->mobile) && $customer->mobile!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="Phone">Phone:</abbr></td>
                            <td class="text-left">{{$customer->mobile ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($customer->telephone) && $customer->telephone!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="Telephone">Telephone:</abbr></td>
                            <td class="text-left">{{$customer->telephone ?? ''}}</td>
                        </tr>
                    @endif
                    @if(isset($customer->email) &&  $customer->email!='')
                        <tr class="text-body">
                            <td class="text-left" style="width: 40%"><abbr title="email">Email Address:</abbr></td>
                            <td class="text-left"><a href="mailto:#" data-original-title="" title="">{{$customer->email ?? ''}}</a></td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </address>
        </div>
        <div class="col-xs-4 ">
            <table class="table-striped text-center">
                <tbody>
                <tr class="text-body">
                    <td class="text-left" style="width: 40%"><abbr title="code">Delivery Note No: </abbr></td>
                    <td class="text-left"><strong>{{$delivery_note->delivery_note_code ?? ''}}</strong></td>
                </tr>
                <tr class="text-body">
                    <td class="text-left" style="width: 40%"><abbr title="date">Date:</abbr></td>
                    <td class="text-left">{{$delivery_note->delivery_note_date ?? ''}}</td>
                </tr>

                @if(isset($delivery_note->po_no) && $delivery_note->po_no!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">PO No:</abbr></td>
                        <td class="text-left">{{$delivery_note->po_no ?? ''}}</td>
                    </tr>
                @endif
                @if(isset($delivery_note->invoice_id) && $delivery_note->invoice_id!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Invoice No:</abbr></td>
                        <td class="text-left">{{$delivery_note->invoice->invoice_code ?? ''}}</td>
                    </tr>
                @endif
                @if(isset($delivery_note->created_by) && $delivery_note->created_by!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Created By:</abbr></td>
                        <td class="text-left">{{$delivery_note->user->username ?? ''}}</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </div>
    </div>
    <!-- Row ends -->


    <div class="">
        <table class="table-striped text-center" style="width: 100%">
            <thead>
            <tr>

                <th class="text-center" style="width: 20%">Item</th>
                <th class="text-center" style="width: 20%">Description</th>
                <th class="text-center" style="width: 8%">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach($delivery_products as $product)
                <tr class="text-body">

                    <td>{{$product->product->item_code}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->qty}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <table style="width: 100% ; margin-top: 40px">
        <tr>
            <td style="width: 50%">
                @if(isset($delivery_note->remarks) &&  $delivery_note->remarks!='')
                    <div class="text-justify">{!! $delivery_note->remarks!!}</div>
                @endif
            </td>
            <td style="width: 10%"></td>
            <td style="width: 40%">

            </th>
        </tr>

    </table>

    <table style="width: 100%; margin-top: 200px">
        <tr>
            <td style="width: 80%">

                Authorized Signature
            </td>
            <td style="width: 30%">

                Customer Signature
            </td>
        </tr>
    </table>

</div>

</body>
</html>
