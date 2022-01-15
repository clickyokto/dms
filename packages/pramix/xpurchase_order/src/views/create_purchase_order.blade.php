@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" rel="stylesheet"/>

@endsection
@section('content')
    @if($page=='purchase_order')
        <?php
        if (!isset($purchase_order->id))
            $header = __('xpurchase_order::purchase_order.headings.new_purchase_order');
        else
            $header = __('xpurchase_order::purchase_order.headings.edit_purchase_order');
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}} {{$purchase_order->purchase_order_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                           id="sales_purchase_order_save_btn">{{ __('xpurchase_order::purchase_order.buttons.save')}}</button>
                        <button class="btn btn-primary"
                           id="purchase_order-update-btn">{{ __('xpurchase_order::purchase_order.buttons.update')}}</button>
                        <button class="btn btn-default"
                           id="generate_purchase_order_pdf">{{ __('xpurchase_order::purchase_order.buttons.generate_purchase_order')}}</button>
                        {{--<button class="btn btn-default"--}}
                           {{--id="generate_mail">{{ __('xpurchase_order::purchase_order.buttons.generate_mail')}}</button>--}}
                        @can('APPROVE_PURCHASE_ORDER')
                        <button class="btn btn-primary" id="purchase_order_approved_btn">Approved</button>
                        @endcan
                        <button class="btn btn-success"
                           id="used_grn_btn">View GRN</button>

                        @if (isset($purchase_order->quotation_id))
                            <button class="btn btn-default"
                               id="view_quotation">{{ __('xpurchase_order::purchase_order.buttons.view')}}</button>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/purchase_order'}}" method="POST" id="purchase_order_form">
                @csrf
                <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="{{ $purchase_order->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $purchase_order->id ?? '' }}">
{{--                <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $purchase_order->quotation_id ?? '' }}">--}}
{{--                <input type="hidden" name="job_card_id" id="job_card_id" value="{{ $purchase_order->job_card_id ?? '' }}">--}}
                <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $purchase_order->supplier_id ?? '' }}">
                <input type="hidden" name="page" id="ref_type" value="PO">
                <input type="hidden" name="record_product_update_id" id="record_product_update_id">
                <input type="hidden" name="record_payment_update_id" id="record_payment_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="purchase_order_status" id="purchase_order_status" value="{{ $purchase_order->status ?? '' }}">
                <input type="hidden" name="checked_recurring_status" id="checked_recurring_status"
                       value="{{ $checked_recurring_status ?? '' }}">

                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="supplier_detail_panel">
                            <div class="card-body">
                                @include('xsupplier::supplier_filter')

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

{{--                        <div class="alert alert-info alert-dismissible" id="supplier_info_alert"></div>--}}

                        <div class="alert alert-info alert-dismissible" id="products_info_alert">

                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                        <div class="card" id="purchase_order_details_panel">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        {{formText(__('xpurchase_order::purchase_order.labels.purchase_order_no'), 'purchase_order_no', $purchase_order->purchase_order_code ?? '', array( 'class' => 'form-control ' , 'id' => 'purchase_order_no' , 'readonly' => 'readonly'))}}
                                    </div>
                                    <div class="col-sm-6">
                                        {{formDate(__('xpurchase_order::purchase_order.labels.purchase_order_date'), 'purchase_order_date_created', isset($purchase_order->purchase_order_date) ? $purchase_order->purchase_order_date :Carbon\Carbon::today()->format('Y-m-d'), array( 'class' => 'form-control' , 'id' => 'purchase_order_date_created'))}}
                                    </div>
                                </div>

                                <span id="display_status">
                                    @if(isset($purchase_order->status) && $purchase_order->status=='D')
                                        <span class="label label-danger">Draft</span>
                                    @elseif(isset($purchase_order->status) && $purchase_order->status=='A')
                                        <span class="label label-success">Completed</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row ends -->
                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    @include('xpurchase_order::product_filter')


                    <div class="form-group">
                    </div>

                    <!-- Row starts -->
                    <div class="row gutter">

                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">


                            @include('xpurchase_order::payment_filter')
                            <div class="card">
                                <div class="card-body">
                                    {{formTextArea(__('xpurchase_order::purchase_order.labels.remarks'), 'remarks', $purchase_order->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks', 'rows' => 2))}}

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="card" id="price_panel">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="sub_total">{{ __('xpurchase_order::purchase_order.labels.subtotal')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="sub_total"
                                                       name="sub_total"
                                                       value="{{ $purchase_order->sub_total ?? '0.00' }}"
                                                       disabled="true">
                                            </div>
                                        </div>
                                        @if(getConfig('INVOICE_TAX') == 'TRUE')
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_vat" name='checked_vat'
                                                           value="vat"
                                                           @if(isset($purchase_order->vat_amount)&& $purchase_order->vat_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_vat">VAT</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="vat_amount"
                                                           name="vat_amount"
                                                           value="{{ $purchase_order->vat_amount ?? '0' }}">
                                                    <div class="input-group-addon">15%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_nbt" name='checked_nbt'
                                                           value="nbt"
                                                           @if(isset($purchase_order->nbt_amount)&& $purchase_order->nbt_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_nbt">NBT</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="nbt_amount"
                                                           name="nbt_amount"
                                                           value="{{ $purchase_order->nbt_amount ?? '0' }}">
                                                    <div class="input-group-addon">2%</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="discount">{{ __('xpurchase_order::purchase_order.labels.discount')}}</label>
                                            <div class="col-sm-9">
                                                <div class="row gutter">
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" id="purchase_order_discount"
                                                               name="purchase_order_discount"
                                                               value="{{ $purchase_order->discount ?? '0' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($purchase_order->discount_type) ? $purchase_order->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'purchase_order_discount_type')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">{{ __('xpurchase_order::purchase_order.labels.total')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="total" name="total"
                                                       value="{{ $purchase_order->total ?? '0.00' }}" disabled="true">
                                            </div>
                                        </div>
                                        @if($page=='purchase_order')
                                            <div class="form-group row gutter">
                                                <label class="col-sm-3 control-label"
                                                       for="paid">{{ __('xpurchase_order::purchase_order.labels.paid')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="label" class="form-control" id="paid" name="paid"
                                                           value="{{ $purchase_order->paid_amount ?? '0.00' }}"
                                                           disabled="true ">
                                                </div>
                                            </div>
                                            <div class="form-group row gutter">
                                                <label class="col-sm-3 control-label"
                                                       for="balance">{{ __('xpurchase_order::purchase_order.labels.balance')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="balance"
                                                           name="balance"
                                                           value="{{ $purchase_order->balance ?? '0.00' }}"
                                                           disabled="true">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(getConfig('ENABLE_INVOICE_RECURRING') == 'TRUE')
                            <div class="card" id="recurring_panel">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <div class="form-group row gutter">
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_recurring"
                                                           name='checked_recurring'
                                                           value="recurring"
                                                           @if(isset($recurring->status)&& $recurring->status!=0) checked @endif/>
                                                    <label
                                                        for="checked_recurring">Recurring</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div id="status_recurring">
                                                    <label for="Status">Status</label>
                                                    <br/>
                                                    <label class="custom-control custom-radio radio-inline">
                                                        <input type="radio" name="status_radio"
                                                               @if(!isset($recurring)) checked
                                                               @endif @if(isset($recurring) && $recurring->status==1) checked
                                                               @endif  value="1">
                                                        <label class="custom-control-label"
                                                               for="customRadio">Active</label>
                                                    </label>
                                                    <label class="custom-control custom-radio radio-inline">
                                                        <input type="radio" name="status_radio"
                                                               @if(isset($recurring) && $recurring->status==0) checked
                                                               @endif value="0">
                                                        <label class="custom-control-label"
                                                               for="customRadio">Disable</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                            </div>
                                        </div>
                                        <div id="other_recurring">
                                            <div class="form-group row gutter">
                                                <div class="col-sm-1 right-text">
                                                </div>
                                                <div class="col-sm-4 left-text">
                                                    {{formDropdown(__('xpurchase_order::purchase_order.labels.recurring_type'), 'recurring_type',getConfig('RECURRING_TYPE'),isset($recurring->billing_cycle) ? $recurring->billing_cycle : getConfigValue('RECURRING_TYPE'), array('class' => 'form-control', 'id' => 'recurring_type'))}}
                                                </div>
                                                <div class="col-sm-7">
                                                </div>
                                            </div>
                                            <div class="form-group row gutter">
                                                <div class="col-sm-1 right-text">
                                                </div>
                                                <div class="col-sm-4" id="recurring_date_div">
                                                      {{ Form::select('recurring_date', array_combine(range(1,30), range(1,30)) , isset($recurring->monthly_generated_date) ? $recurring->monthly_generated_date : ''  , array('class' => 'form-control select2' , 'id' => 'recurring_date')) }}
                                                </div>
                                                <div class="col-sm-1 right-text">
                                                </div>
                                                <div class="col-sm-4"  id="recurring_month_div">
                                                   {!! Form::selectMonth('recurring_month', isset($recurring->yearly_generated_month) ? $recurring->yearly_generated_month : ''  , array('class' => 'form-control select2' , 'id' => 'recurring_month')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endif
                        </div>
                    </div>
                    <div class="card" id="supplier_comment_content">
                        @include('xgeneral::comment_section')
                    </div>
                </div>
                <!-- Row ends -->
            </form>
        </div>
        <!-- Main container ends -->
    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/purchase_order_js.js') }}"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $("#remarks").Editor("setText", "{{$purchase_order->remarks ?? ''}}")

            $('#recurring_month').hide('slow');

            @if(isset($purchase_order->project_id))
            $('#project_code_selected').val({{$purchase_order->project_id}}).trigger('change');
            $('#overlay').hide('slow');
            @elseif(isset($purchase_order->supplier_id))
            $('#supplier_code_selected').val({{$purchase_order->supplier_id}}).trigger('change.select2');
            changeSupplier.run($("#supplier_id").val(), '', '');
            @endif

            if ($('#checked_recurring_status').val() == 0 || $('#checked_recurring_status').val() == '') {
                $('#status_recurring').hide();
                $('#other_recurring').hide();
            }

            @if(isset($purchase_order->status) && $purchase_order->status != 'D')
            $('#product-details-card').hide('slow');
            $('#supplier_detail_panel :input').prop("disabled", true);
            $('#price_panel :input').prop("disabled", true);
            $('#purchase_order_details_panel :input').prop("disabled", true);
            @endif

            @if(isset($purchase_order->assigned_user))
            $('#staff_member_id_selected').val({{$purchase_order->assigned_user}}).trigger('change');
                @else
            var selectedstaffidName = new Option('Select Staff id', '', true, true);
            var selectedstaffnameName = new Option('Select Staff Name ', '', true, true);
            $('#staff_member_id_selected').append(selectedstaffidName).trigger('change');
            $('#staff_member_name_selected').append(selectedstaffnameName).trigger('change');
            @endif

        });
    </script>
@endsection

