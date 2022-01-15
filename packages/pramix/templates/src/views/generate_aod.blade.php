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

                <h3 class="text-primary"><strong><u>ADVICE OF DESPATCH</u></strong></h3>

        </div>
    </div>

    <div class="row ">

        <div class="col-xs-8">
            <address>
                <table class="table-striped text-center">
                    <tbody>
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="code">{{ __('xcustomer::customer.labels.code')}}: </abbr></td>
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
                            <td class="text-left" style="width: 40%"><abbr title="email">{{ __('xaod::aod.labels.email')}}:</abbr></td>
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
                    <td class="text-left" style="width: 40%"><abbr title="code">AOD No: </abbr></td>
                    <td class="text-left"><strong>{{$aod->aod_code ?? ''}}</strong></td>
                </tr>
                <tr class="text-body">
                    <td class="text-left" style="width: 40%"><abbr title="date">Date:</abbr></td>
                    <td class="text-left">{{$aod->aod_date ?? ''}}</td>
                </tr>

                @if(isset($aod->po_no) && $aod->po_no!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">PO No:</abbr></td>
                        <td class="text-left">{{$aod->po_no ?? ''}}</td>
                    </tr>
                @endif
                @if(isset($aod->quotation_id) && $aod->quotation_id!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Quotation No:</abbr></td>
                        <td class="text-left">{{$aod->quotation->quotation_code ?? ''}}</td>
                    </tr>
                @endif
                @if(isset($aod->created_by) && $aod->created_by!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Created By:</abbr></td>
                        <td class="text-left">{{$aod->user->username ?? ''}}</td>
                    </tr>
                @endif
                @if(isset($aod->assigned_user) && $aod->assigned_user!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Code:</abbr></td>
                        <td class="text-left">{{$aod->staff_member->fname ?? ''}}</td>
                    </tr>
                @endif
                @if($aod->vat_amount != 0 || $aod->nbt_amount != 0)
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">VAT No:</abbr></td>
                        <td class="text-left">{{getConfigArrayValueByKey('COMPANY_DETAILS','tax_no')}}</td>
                    </tr>

                @if(isset($customer->tax_no) && $customer->tax_no!='')
                    <tr class="text-body">
                        <td class="text-left" style="width: 40%"><abbr title="name">Cuatomer VAT No:</abbr></td>
                        <td class="text-left">{{$customer->tax_no ?? ''}}</td>
                    </tr>
                @endif
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
                <th class="text-center" style="width: 20%">Description</th>
                <th class="text-center" style="width: 8%">Model No</th>
                <th class="text-center" style="width: 13%">Serial No</th>
                <th class="text-center" style="width: 13%">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach($aodproducts as $product)
                <tr class="text-body">
                    <td>{{$product->description}}</td>
                    <td>{{$product->model_no}}</td>
                    <td>{{$product->serial_no}}</td>
                    <td >{{$product->qty}}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <table style="width: 100% ; margin-top: 200px">
        <tr>
            <td style="width: 33%">
                Authorized Signature
            </td>
            <td style="width: 33%">
                Prepared By
            </td>
            <td style="width: 33%">
                Customer Signature
            </th>
        </tr>

    </table>

</div>

</body>
</html>
