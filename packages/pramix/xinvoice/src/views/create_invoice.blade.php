@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" rel="stylesheet"/>

@endsection
@section('content')
    @if($page=='invoice')
        <?php
        if (!isset($invoice->id))
            $header = 'New Order';
        else{


            $header = 'Edit';


         //   $header = __('xinvoice::invoice.headings.edit_invoice');
            }
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}} <span id="invoice_type_label"></span> - {{$invoice->invoice_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                           id="sales_invoice_save_btn">{{ __('xinvoice::invoice.buttons.save')}}</button>
                        <button class="btn btn-primary"
                           id="invoice-update-btn">{{ __('xinvoice::invoice.buttons.update')}}</button>
                        <button class="btn btn-primary"
                                id="invoice-save-and-new-btn">{{ __('xinvoice::invoice.buttons.save_and_new')}}</button>
                        {{--@if(isset($invoice->status) &&  $invoice->status == 'Q' )--}}
                            {{--@else--}}

                        <button class="btn btn-primary"
                                id="convert_ready_to_dispatch">Convert ready to dispatch</button>
                        <button class="btn btn-primary"
                                id="convert_to_invoice">Convert to Invoice</button>
{{--@endif--}}
                        <button class="btn btn-primary"
                                id="generate_invoice_pdf">Print</button>
                        @if (isset($credit_note) && count($credit_note)!= 0)
                            <button class="btn btn-danger"
                                    id="view_credit_note">Credit Note</button>
                        @endif
                        @if (isset($invoice->quotation_id))
                            <button class="btn btn-default"
                               id="view_quotation">{{ __('xinvoice::invoice.buttons.view')}}</button>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/invoice'}}" method="POST" id="invoice_form">
                @csrf
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $invoice->id ?? '' }}">
                <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $invoice->quotation_id ?? '' }}">
                <input type="hidden" name="job_card_id" id="job_card_id" value="{{ $invoice->job_card_id ?? '' }}">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $invoice->customer_id ?? '' }}">
                <input type="hidden" name="page" id="ref_type" value="IN">
                <input type="hidden" name="record_product_update_id" id="record_product_update_id">
                <input type="hidden" name="record_payment_update_id" id="record_payment_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="invoice_status" id="invoice_status" value="{{ $invoice->status ?? '' }}">


                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="customer_detail_panel">
                            <div class="card-body">
                                @include('xcustomer::customer_filter')
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="quick_sell" value="1" name="quick_sell">
                                    <label class="form-check-label" for="exampleCheck1">Quick sell</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">

                        <div class="alert alert-info alert-dismissible" id="customer_info_alert"></div>

                        <div class="alert alert-info alert-dismissible" id="products_info_alert">

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card" id="invoice_details_panel">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-12">
                                        {{formText(__('xinvoice::invoice.labels.invoice_no'), 'invoice_no', $invoice->invoice_code ?? '', array( 'class' => 'form-control ' , 'id' => 'invoice_no' , 'readonly' => 'readonly'))}}
                                    </div>
                                    <div class="col-sm-12">
                                        {{formDate(__('xinvoice::invoice.labels.invoice_date'), 'invoice_date_created', isset($invoice->invoice_date) ? $invoice->invoice_date :Carbon\Carbon::today()->format('Y-m-d'), array( 'class' => 'form-control' , 'id' => 'invoice_date_created'))}}
                                    </div>
                                    <div class="col-sm-12">

                                        @php
                                            $users = \Pramix\XUser\Models\User::pluck('username','id');
                                        @endphp
                                        {{formDropdown('Rep', 'rep',$users, isset($invoice->rep_id) ? $invoice->rep_id :Auth::id(), array('class' => 'form-control select2 validate[required]', 'id' => 'rep'))}}
                                    </div>
                                </div>


                                <span id="display_status">
                                        <span class="label label-danger" id="invoice_status_label"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row ends -->
                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    @include('xproduct::product_filter', ['only_available_stock_product' => false])

                    <div class="form-group">
                    </div>

                    <!-- Row starts -->
                    <div class="row gutter">

                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">



                            <div class="card">
                                <div class="card-body">
                                    {{formTextArea(__('xinvoice::invoice.labels.remarks'), 'remarks', $invoice->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks', 'rows' => 2))}}

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="card" id="price_panel">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="sub_total">{{ __('xinvoice::invoice.labels.subtotal')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="sub_total"
                                                       name="sub_total"
                                                       value="{{ $invoice->sub_total ?? '0.00' }}"
                                                       disabled="true">
                                            </div>
                                        </div>
                                        @if(getConfig('INVOICE_TAX') == 'TRUE')
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_vat" name='checked_vat'
                                                           value="vat"
                                                           @if(isset($invoice->vat_amount)&& $invoice->vat_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_vat">VAT</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="vat_amount"
                                                           name="vat_amount"
                                                           value="{{ $invoice->vat_amount ?? '0' }}">
                                                    <div class="input-group-addon">15%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_nbt" name='checked_nbt'
                                                           value="nbt"
                                                           @if(isset($invoice->nbt_amount)&& $invoice->nbt_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_nbt">NBT</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="nbt_amount"
                                                           name="nbt_amount"
                                                           value="{{ $invoice->nbt_amount ?? '0' }}">
                                                    <div class="input-group-addon">2%</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="discount">{{ __('xinvoice::invoice.labels.discount')}}</label>
                                            <div class="col-sm-9">
                                                <div class="row gutter">
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" id="invoice_discount"
                                                               name="invoice_discount"
                                                               value="{{ $invoice->discount ?? '0' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($invoice->discount_type) ? $invoice->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'invoice_discount_type')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">{{ __('xinvoice::invoice.labels.total')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="total" name="total"
                                                       value="{{ $invoice->total ?? '0.00' }}" disabled="true">
                                            </div>
                                        </div>
                                        @if($page=='invoice')
                                            <div class="form-group row gutter">
                                                <label class="col-sm-3 control-label"
                                                       for="paid">{{ __('xinvoice::invoice.labels.paid')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="label" class="form-control" id="paid" name="paid"
                                                           value="{{ $invoice->paid_amount ?? '0.00' }}"
                                                           disabled="true ">
                                                </div>
                                            </div>


                                            <div class="form-group row gutter">
                                                <label class="col-sm-3 control-label"
                                                       for="paid">Returns</label>
                                                <div class="col-sm-9">
                                                    <input type="label" class="form-control" id="returned_amount" name="returned_amount"
                                                           value="{{ $invoice->returned_amount ?? '0.00' }}"
                                                           disabled="true ">
                                                </div>
                                            </div>

                                            <div class="form-group row gutter">
                                                <label class="col-sm-3 control-label"
                                                       for="balance">{{ __('xinvoice::invoice.labels.balance')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="balance"
                                                           name="balance"
                                                           value="{{ $invoice->balance ?? '0.00' }}"
                                                           disabled="true">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12">
                            @include('xinvoice::payment_filter')
                        </div>
                    </div>
                    <div class="card" id="customer_comment_content">

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
    <script src="{{ asset('/pramix/js/invoice_js.js?v=4') }}"></script>


@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            @if(isset($invoice) && $invoice->status == 'Q')
$('#convert_ready_to_dispatch').hide();
            $('#convert_to_invoice').hide();
                @endif


            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $("#remarks").Editor("setText", "{{$invoice->remarks ?? ''}}")

            $('#recurring_month').hide('slow');

            @if(isset($invoice->project_id))
            $('#project_code_selected').val({{$invoice->project_id}}).trigger('change');
            $('#overlay').hide('slow');
            @elseif(isset($invoice->customer_id))
            $('#customer_id_selected').val({{$invoice->customer_id}}).trigger('change.select2');
            changeCustomer.run($("#customer_id").val(), '', '');
            @endif

            if ($('#checked_recurring_status').val() == 0 || $('#checked_recurring_status').val() == '') {
                $('#status_recurring').hide();
                $('#other_recurring').hide();
            }

            @if(isset($invoice->status) &&  $invoice->status == 'Q' )
            $( "#quick_sell" ).prop( "checked", true );
                    @endif

            @if(isset($invoice->status) && ($invoice->status == 'I' || $invoice->status == 'Q') )
            $('#product-details-panel').hide('slow');
            $('#customer_detail_panel :input').prop("disabled", true);
           // $('#price_panel :input').prop("disabled", true);
            $('#invoice_details_panel :input').prop("disabled", true);
            @endif

            @if(isset($invoice->assigned_user))
            $('#staff_member_id_selected').val({{$invoice->assigned_user}}).trigger('change');
                @else
            var selectedstaffidName = new Option('Select Staff id', '', true, true);
            var selectedstaffnameName = new Option('Select Staff Name ', '', true, true);
            $('#staff_member_id_selected').append(selectedstaffidName).trigger('change');
            $('#staff_member_name_selected').append(selectedstaffnameName).trigger('change');
            @endif
        });
    </script>
@endsection

