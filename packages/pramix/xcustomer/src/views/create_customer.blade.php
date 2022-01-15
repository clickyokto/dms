@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        @if(isset($customer->id))
                            <h4>{{ __('xcustomer::customer.headings.edit_customer')}}</h4>
                        @else
                            <h4>{{ __('xcustomer::customer.headings.new_customer')}}</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="save_button_group">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                           id="customer-save-btn">{{ __('xcustomer::customer.buttons.save')}}</button>
                        <button class="btn btn-primary"
                           id="customer-save-and-new">{{ __('xcustomer::customer.buttons.save_and_new')}}</button>
                        <button class="btn btn-primary"
                           id="customer-update-btn">{{ __('xcustomer::customer.buttons.update')}}</button>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">

            <form action="{{url('/').'/customer'}}" method="POST" id="create_customer_form">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id ?? '' }}">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="isajax" id="isajax" value="{{ Request::ajax() }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $customer->id ?? '' }}">
                <input type="hidden" name="ref_type" id="ref_type" value="C">
                <input type="hidden" name="telephone_country" id="telephone_country" value="">
                <input type="hidden" name="mobile_country" id="mobile_country" value="">
                <input type="hidden" name="shipping_address_status" id="shipping_address_status"
                       value="{{ $shipping_address_status ?? '' }}">
                <input type="hidden" name="add_as_company_name" id="add_as_company_name"
                       value="{{ $customer->customer_type?? '' }}">
            <div class="row gutter">


                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card" id="customer-details-card">
                            <div class="card-header">
                                    <h4>{{ __('xcustomer::customer.headings.panel_customer_details')}}</h4>
                            </div>

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.business_name'), 'business_name', $customer->business_name ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'business_name'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{formDropdown('Invoice type', 'invoice_type',getConfig('CUSTOMER_INVOICE_TYPES'),isset($customer->invoice_type) ? $customer->invoice_type : getConfigValue('CUSTOMER_INVOICE_TYPES'), array('class' => 'form-control', 'id' => 'customer_invoice_type'))}}

                                        {{--{{formDropdown(__('xcustomer::customer.labels.customer_type'), 'customer_type',getConfig('CUSTOMER_TYPE'),isset($customer->customer_type) ? $customer->customer_type : getConfigValue('CUSTOMER_TYPE'), array('class' => 'form-control', 'id' => 'customer_type'))}}--}}

                                    </div>
                                </div>
                                <div class="row gutter" >
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.company_name'), 'company_name', $customer->company_name ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'company_name'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.mobile'), 'mobile', $customer->mobile ?? '', array( 'class' => 'form-control' , 'id' => 'customer_mobile'))}}
                                    </div>
                                </div>




                                <div class="row gutter">

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.telephone'), 'telephone', $customer->telephone ?? '', array( 'class' => 'form-control' , 'id' => 'customer_telephone'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText('Fax', 'fax', $customer->fax ?? '', array( 'class' => 'form-control' , 'id' => 'fax'))}}
                                    </div>
                                </div>



                                <div class="row gutter">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        {{formDropdown(__('xcustomer::customer.labels.title'), 'title',getConfig('TITLE'),isset($customer->title) ? $customer->title : getConfigValue('TITLE'), array('class' => 'form-control', 'id' => 'title'))}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                        {{ formText(__('xcustomer::customer.labels.first_name'), 'first_name', $customer->fname ?? '', array( 'class' => 'form-control ' , 'id' => 'first_name'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.last_name'), 'last_name', $customer->lname ?? '', array( 'class' => 'form-control' , 'id' => 'last_name'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.nic'), 'nic', $customer->nic ?? '', array( 'class' => 'form-control' , 'id' => 'nic'))}}
                                    </div>
                                </div>

                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{formEmail(__('xcustomer::customer.labels.email'), 'email', $customer->email ?? '', array( 'class' => 'form-control' , 'id' => 'customer_email'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.website'), 'website', $customer->website ?? '', array( 'class' => 'form-control' , 'id' => 'website'))}}
                                    </div>
                                </div>

                                {{formTextArea(__('xcustomer::customer.labels.remarks'), 'customer_remarks', $customer->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'customer_remarks', 'rows' => 3))}}
                            </div>
                        </div>
                        <div class="card" id="payment-details-card">
                            <div class="card-header">
                                <h4>{{ __('xcustomer::customer.headings.panel_customer_payment_details')}}</h4>
                            </div>

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="outstanding_amount">{{__('xcustomer::customer.labels.outstanding_amount')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rs.</div>
                                                <input type="text" class="form-control" id="outstanding_amount"
                                                       name="outstanding_amount"
                                                       value="{{$customer->outstanding_amount ?? ''}}" readonly>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.outstanding_limit'), 'outstanding_limit', $customer->outstanding_limit ?? 600000, array( 'class' => 'form-control validate[required]', 'id' => 'outstanding_limit'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.outstanding_day_limit'), 'outstanding_day_limit', $customer->outstanding_max_days ?? getConfig('OUTSTANDING_MAX_DAYS'), array( 'class' => 'form-control validate[required]', 'id' => 'outstanding_day_limit'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xcustomer::customer.labels.tax_no'), 'tax_no', $customer->tax_no ?? '', array( 'class' => 'form-control' , 'id' => 'tax_no'))}}
                                    </div>

                                </div>

                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formNumber(__('xcustomer::customer.labels.discount'), 'discount', $customer->discount ?? '', array( 'class' => 'form-control' , 'id' => 'discount'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{formDropdown(__('xcustomer::customer.labels.discount_type'), 'discount_type',getConfig('DISCOUNT_TYPE'),isset($customer->discount_type) ? $customer->discount_type : getConfigValue('DISCOUNT_TYPE'), array('class' => 'form-control', 'id' => 'discount_type'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card" id="payment-details-card">
                        <div class="card-header">
                            <h4>Customer Area</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gutter">
                                {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">--}}
                                    {{--{{formDropdown('Area', 'area',\Pramix\XGeneral\Models\AreaModel::pluck('code','id'), isset($customer->area_id) ? $customer->area_id :'', array('class' => 'form-control select2 validate[required]', 'id' => 'area'))}}--}}
                                {{--</div>--}}
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    @php
                                        $roles = \Pramix\XUser\Models\User::role('REPRESENTATIVE')->pluck('username','id');
                                    @endphp
                                    {{formDropdown('Rep', 'rep',$roles, isset($customer->rep_id) ? $customer->rep_id :'', array('class' => 'form-control select2 validate[required]', 'id' => 'rep'))}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card" id="business_address_panel">
                        <div class="card-header">
                            <h4>{{ __('xcustomer::customer.headings.panel_business_address')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{ formText(__('xcustomer::customer.labels.street1'), 'business_street1', $business_address->address_line_1 ?? '', array( 'class' => 'form-control' , 'id' => 'business_street1'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{ formText(__('xcustomer::customer.labels.street2'), 'business_street2', $business_address->address_line_2 ?? '', array( 'class' => 'form-control' , 'id' => 'business_street2'))}}
                                </div>
                            </div>
                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.district'), 'business_district_id',\Pramix\XGeneral\Models\DistrictsModel::pluck('name_en','id'), isset($business_address->district_id) ? $business_address->district_id :'', array('class' => 'form-control select2', 'id' => 'business_district_id'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.city'), 'business_city_id',\Pramix\XGeneral\Models\CityModel::pluck('name_en','id'), isset($business_address->city_id) ? $business_address->city_id :'', array('class' => 'form-control select2', 'id' => 'business_city_id'))}}
                                </div>
                            </div>
                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.country'), 'business_country', $countryList , $business_address->country ?? '' , array('class' => 'form-control select2', 'id' => 'business_country'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                </div>
                            </div>

                            {{formTextArea(__('xcustomer::customer.labels.remarks'), 'business_remarks', $business_address->description ?? '', array( 'class' => 'form-control' , 'id' => 'business_remarks', 'rows' => 3))}}

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox checkbox-inline">
                            <input type="checkbox" id="add_shipping" value="shipping_address"/>
                            <label
                                for="add_shipping">{{__('xcustomer::customer.labels.add_shipping_address')}}</label>
                        </div>
                    </div>
                    <div class="card" id="shipping_address_panel">
                        <div class="card-header">
                            <h4>{{ __('xcustomer::customer.headings.panel_shipping_address')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{ formText(__('xcustomer::customer.labels.street1'), 'shipping_street1', $shipping_address->address_line_1 ?? '', array( 'class' => 'form-control' , 'id' => 'shipping_street1'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{ formText(__('xcustomer::customer.labels.street2'), 'shipping_street2', $shipping_address->address_line_2 ?? '', array( 'class' => 'form-control' , 'id' => 'shipping_street2'))}}
                                </div>
                            </div>
                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.district'), 'shipping_district_id',\Pramix\XGeneral\Models\DistrictsModel::pluck('name_en','id'), isset($shipping_address->district_id) ? $shipping_address->district_id :'', array('class' => 'form-control select2', 'id' => 'shipping_district_id'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.city'), 'shipping_city_id',\Pramix\XGeneral\Models\CityModel::pluck('name_en','id'), isset($shipping_address->city_id) ? $shipping_address->city_id :'', array('class' => 'form-control select2', 'id' => 'shipping_city_id'))}}
                                </div>
                            </div>

                            <div class="row gutter">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    {{formDropdown(__('xcustomer::customer.labels.country'), 'shipping_country', $countryList , $shipping_address->country ?? '' , array('class' => 'form-control select2', 'id' => 'shipping_country'))}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                </div>
                            </div>
                            {{formTextArea(__('xcustomer::customer.labels.remarks'), 'shipping_remarks', $shipping_address->description ?? '', array( 'class' => 'form-control' , 'id' => 'shipping_remarks', 'rows' => 3))}}
                        </div>
                    </div>
                </div>



                </div>

            </div>
            </form>
            @if(Request::ajax()==0)
                <div class="card" id="customer_comment_content">
                    @include('xgeneral::comment_section')
                </div>
            @endif
        </div>
    </div>
@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/customer_js.js') }}"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            if ($('#shipping_address_status').val() == 0 || $('#shipping_address_status').val() == '') {
                $('#shipping_address_panel').hide();
            }

            if ($('#add_as_company_name').val() == 'C' || $('#add_as_company_name').val() == '') {
                $('#company_detail_panel').hide();
            }


            $("#customer_mobile").intlTelInput({
                    preferredCountries: ['{{config('system.default_country_code')}}'],
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc
                }
            );
            $("#customer_telephone").intlTelInput({
                    preferredCountries: ['{{config('system.default_country_code')}}'],
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc
                }
            );

            $("#fax").intlTelInput({
                    preferredCountries: ['{{config('system.default_country_code')}}'],
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc
                }
            );
            //My new business address

            $('#business_country , #shipping_country').val('{{config('system.default_country')}}').trigger('change');

            @if(isset($business_address->district_id))
            $('#business_district_id').val({{$business_address->district_id}}).trigger('change.select2');
            $('#business_city_id').val({{$business_address->city_id}}).trigger('change.select2');
                @else
            var selecteddistrict = new Option('Please select district', '', true, true);
            $('#business_district_id').append(selecteddistrict).trigger('change');

            var selectedcity = new Option('Please select city', '', true, true);
            $('#business_city_id').append(selectedcity).trigger('change');
            @endif


            @if(isset($business_address->country))
            $('#business_country').val('{{$business_address->country}}').trigger('change.select2');
            @endif

            //My new shipping

            @if(isset($shipping_address->district_id))
            $('#shipping_district_id').val({{$shipping_address->district_id}}).trigger('change.select2');
            $('#shipping_city_id').val({{$shipping_address->city_id}}).trigger('change.select2');
                @else
            var selecteddistrict = new Option('Please select district', '', true, true);
            $('#shipping_district_id').append(selecteddistrict).trigger('change');

            var selectedcity = new Option('Please select city', '', true, true);
            $('#shipping_city_id').append(selectedcity).trigger('change');
            @endif

            @if(isset($shipping_address->country))
            $('#shipping_country').val('{{$shipping_address->country}}').trigger('change.select2');
            @endif


            // var selectarea = new Option('Select customer area', '', true, true);
            // $('#area').append(selectarea).trigger('change');

            var selectedrep = new Option('Select rep', '', true, true);
            $('#rep').append(selectedrep).trigger('change');



         //   $('#area').val("{{$customer->area_id ?? ''}}").trigger('change.select2');
            $('#rep').val("{{$customer->rep_id ?? ''}}").trigger('change.select2');

        });
    </script>
@endsection
