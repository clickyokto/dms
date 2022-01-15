@php
    if(!isset($create_new))
    $create_new = TRUE;
@endphp

<div class="row gutter">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        {{formDropdown('Customer Name', 'customer_id_selected',[], '', array('class' => 'form-control select2 ', 'id' => 'customer_id_selected'))}}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        {{formDropdown('Customer Code', 'customer_name_selected',[], '', array('class' => 'form-control select2 ', 'id' => 'customer_name_selected'))}}

    </div>

</div>

{{--<div class="row gutter">--}}

    {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">--}}
        {{--{{ formText(__('xcustomer::customer.labels.mobile'), 'mobile', '', array( 'class' => 'form-control' , 'id' => 'search_mobile'))}}--}}
    {{--</div>--}}
    {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">--}}
        {{--{{ formText(__('xcustomer::customer.labels.telephone'), 'telephone', '', array( 'class' => 'form-control' , 'id' => 'search_telephone'))}}--}}
    {{--</div>--}}
{{--</div>--}}

@if($create_new)
<div class="row gutter">

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        @can('ADD_CUSTOMER')
            <a class="btn btn-link" href="#"
               id="create_new_customer_model">{{__('xcustomer::customer.buttons.new_customer')}}</a>
        @endcan

    </div>
</div>
    @endif



