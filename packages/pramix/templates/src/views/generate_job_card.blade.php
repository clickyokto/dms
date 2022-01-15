<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>


        body {
            font-size: 12px;
            font-family: DejaVu Sans;
            text-transform: uppercase;
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


    <div class="row ">

        <div class="col-xs-7">

            <h2><strong>{{getConfigArrayValueByKey('COMPANY_DETAILS','company_name')}}</strong></h2>
            {{getConfigArrayValueByKey('COMPANY_DETAILS','street1')}} {{getConfigArrayValueByKey('COMPANY_DETAILS','street2')}}, {{getConfigArrayValueByKey('COMPANY_DETAILS','city')}}. <br>

            Telephone : {{getConfigArrayValueByKey('COMPANY_DETAILS','telephone')}} | Mobile : {{getConfigArrayValueByKey('COMPANY_DETAILS','mobile')}}<br>
            Email : {{getConfigArrayValueByKey('COMPANY_DETAILS','email')}}
            <address>

                <div style="background-color:#B2BAB2; padding:3px; margin-top:6px;">Bill To</div>

                <strong>{{$customer->title}}. {{$customer->fullname ?? ''}}</strong><br>
                @if(isset($business_address->address_line_1) && $business_address->address_line_1!='')
                    <br> {{$business_address->address_line_1 ?? ''}},
                @endif
                @if(isset($business_address->address_line_2) && $business_address->address_line_2!='')
                    <br> {{$business_address->address_line_2 ?? ''}},
                @endif
                @if(isset($business_address->city_name) && $business_address->city_name!='')
                    <br> {{$business_address->city_name ?? ''}},
                @endif
                @if(isset($business_address->district_name) && $business_address->district_name!='')
                    <br>{{$business_address->district_name ?? ''}}, </abbr> <br>
                @endif
                @if(isset($customer->email) &&  $customer->email!='')
                    <abbr title="email">{{ __('xjob_card::job_card.labels.email')}}:</abbr>
                    <a href="mailto:#" data-original-title="" title="">{{$customer->email ?? ''}}</a><br>
                @endif
                @if(isset($customer->mobile) && $customer->mobile!='')
                    <abbr title="Phone">Phone:</abbr> {{$customer->mobile ?? ''}}<br>
                @endif
                @if(isset($customer->telephone) && $customer->telephone!='')
                    <abbr title="Fax">Telephone:</abbr> {{$customer->telephone ?? ''}}<br>
                @endif

                @if(isset($vehicle))<span class="text-info">Vehicle : {{$vehicle->vehicle_no ?? ''}}</span>@endif
            </address>
        </div>
        <div class="col-xs-5 ">

            @if($job_card->vat_amount != 0 || $job_card->nbt_amount != 0)
                <h3 class="text-primary"><strong><u>TAX JOB CARD</u></strong></h3>
            @else
                <h3 class="text-primary"><strong><u>JOB CARD</u></strong></h3>
            @endif
            <h4 class="heading-block">Job_Card : {{$job_card->job_card_code ?? ''}}</h4>
            <span class="text-info">Date : {{$job_card->job_card_date ?? ''}}</span><br>

            <div style="margin-left:20px; padding-top:25px">
                <img src="{{public_path('/uploads/company_logo/2019_11/logo.jpg')}}" >
            </div>

        </div>
    </div>
    <!-- Row ends -->
    <?php
    $show_product_discount = FALSE;
    foreach ($job_card_products as $product) {
        if($product->discount!='' && $product->discount!=0)
        {
            $show_product_discount = TRUE;
            break;
        }
    }

    ?>

    <div class="">
        <table class="table-striped text-center" style="width: 100%;margin-top: 25px; ">
            <thead>
            <tr style="background-color:#B2BAB2;">

                <th class="text-center" style="width: 20%; padding:5px">Item</th>
                <th class="text-center" style="width: 20%; padding:5px">Description</th>
                <th class="text-right" style="width: 8%; padding:5px">Quantity</th>
                <th class="text-right" style="width: 13%; padding:5px">Unit Price</th>
                @if($show_product_discount)
                    <th class="text-right" style="width: 13%; padding:5px">Sub Total</th>
                    <th class="text-right" style="width: 13%; padding:5px">Discount</th>
                @endif
                <th class="text-right" style="width: 13%; padding:5px">Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($job_card_products as $product)
                <tr class="text-body">

                    <td class="text-left">{{$product->product->item_code}}</td>
                    <td >{{$product->description}}</td>
                    <td class="text-right">{{$product->qty}}</td>
                    <td class="text-right">{{ number_format($product->unit_price, 2)}}</td>
                    @if($show_product_discount)
                        <td class="text-right">{{number_format($product->qty*$product->unit_price, 2)}}</td>
                        <td>
                            @if($product->discount!='' || $product->discount!=0)
                                @if($product->discount_type=='P')
                                    {{$product->discount}}  %
                                @else
                                    {{number_format($product->discounte, 2)}}
                                @endif
                            @else --
                            @endif
                        </td>
                    @endif
                    <td class="text-right">{{number_format($product->sub_total, 2)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <table style="width: 100% ; margin-top: 25px">
        <tr>
            <td style="width: 50%">
                @if(isset($job_card->current_mileage) &&  $job_card->current_mileage!=0)
                    <div>Meter Reading at Service : {!! $job_card->current_mileage!!} KM</div>
                @endif
                @if(isset($job_card->next_service_due_mileage) &&  $job_card->next_service_due_mileage!=NULL)
                    <div>Next Service Due at {!! $job_card->next_service_due_mileage!!} KM</div>
                @endif


                @if(isset($job_card->remarks) &&  $job_card->remarks!='')
                    <br>Remarks<br>
                    <div class="text-justify">{!! $job_card->remarks!!}</div>
                @endif
            </td>
            <td style="width: 10%"></td>
            <td style="width: 40%">

                <table style="width: 100%">
                    <tbody class="text-body2">
                    <tr>
                        <td class="total"><strong>Sub Total</strong></td>

                        <td class="text-right">{{\App\Http\Helper::formatPrice($job_card->sub_total)}}</td>
                    </tr>
                    @if($job_card->discount!='' && $job_card->discount!=0)
                        <tr>
                            <td class="total"><strong>Discount</strong></td>
                            @if($job_card->discount_type=='P')
                                <td class="text-right">
                                    {{$job_card->discount}}%
                                </td>
                            @else
                                <td class="text-right">
                                    {{\App\Http\Helper::formatPrice($job_card->discount)}}
                                </td>
                            @endif
                        </tr>
                    @endif
                    @if(isset($job_card->vat_amount) && $job_card->vat_amount!=0)
                        <tr>
                            <td class="total"><strong>VAT Amount (15%)</strong></td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($job_card->vat_amount)}}</td>
                        </tr>
                    @endif
                    @if(isset($job_card->nbt_amount) && $job_card->nbt_amount !=0)
                        <tr>
                            <td class="total"><strong>NBT Amount (2%)</strong></td>
                            <td class="text-right">{{\App\Http\Helper::formatPrice($job_card->nbt_amount)}}</td>
                        </tr>
                    @endif
                    <tr class="font-text-bold ">

                        <td class="total"><strong>Total Amount Payable</strong></td>
                        <td class="text-right"> {{\App\Http\Helper::formatPrice($job_card->total)}}</td>
                    </tr>
                    <tr class="font-text-bold">
                        <td class="total"><strong>Paid Amount</strong></td>
                        <td class="text-right">{{\App\Http\Helper::formatPrice($job_card->paid_amount)}}</td>
                    </tr>
                    <tr class="font-text-bold">
                        <td class="total"><strong>Amount Due</strong></td>
                        <td class="text-right">
                            <strong>{{\App\Http\Helper::formatPrice($job_card->balance)}}</strong></td>
                    </tr>
                    </tbody>
                </table>

            </th>
        </tr>

    </table>

    <table style="width: 100%; margin-top:70px">
        <tr>
            <td style="width: 80%">


            </td>
            <td style="width: 30%" class="text-center">
                ....................................................<br>
                Manager
            </td>
        </tr>
    </table>
    <div class="row">
        <div class="col-xs-12 text-center">
            <strong>THANK YOU FOR YOUR BUSINESS</strong>
        </div>
    </div>
</div>

</body>
</html>
