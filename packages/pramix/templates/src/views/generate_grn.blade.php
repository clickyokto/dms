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

            <h3 class="text-primary"><strong><u>GRN</u></strong></h3>

        </div>
    </div>

    <div class="row ">
        <div class="col-xs-8">
            <address>
                <strong>{{$supplier->fullname ?? ''}}</strong>
                @if(isset($business_address->address_line_1) && $business_address->address_line_1!='')
                    <br> {{$business_address->address_line_1 ?? ''}},
                @endif
                @if(isset($business_address->address_line_2) && $business_address->address_line_2!='')
                    {{$business_address->address_line_2 ?? ''}},
                @endif
                @if(isset($business_address->city_name) && $business_address->city_name!='')
                    <br> {{$business_address->city_name ?? ''}},
                @endif
                @if(isset($business_address->district_name) && $business_address->district_name!='')
                    {{$business_address->district_name ?? ''}}, </abbr> <br>
                @endif
                @if(isset($supplier->email) &&  $supplier->email!='')
                    <abbr title="email">{{ __('xinvoice::invoice.labels.email')}}:</abbr>
                    <a href="mailto:#" data-original-title="" title="">{{$supplier->email ?? ''}}</a><br>
                @endif
                @if(isset($supplier->mobile) && $supplier->mobile!='')
                    <abbr title="Phone">Phone:</abbr> {{$supplier->mobile ?? ''}}<br>
                @endif
                @if(isset($supplier->telephone) && $supplier->telephone!='')
                    <abbr title="Fax">Telephone:</abbr> {{$supplier->telephone ?? ''}}
                @endif
            </address>
        </div>
        <div class="col-xs-4 ">


            <h4 class="heading-block">GRN # : {{$grn->grn_code ?? ''}}</h4>
            <p class="text-info">Date : {{$grn->grn_date ?? ''}}</p>

        </div>
    </div>

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
            @foreach($grn_products as $product)
                <tr class="text-body">
                    <td>{{$product->product->item_code}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->delivered_qty}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
