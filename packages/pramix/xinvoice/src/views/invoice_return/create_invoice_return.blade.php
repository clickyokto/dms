@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />

@endsection
@section('content')
    @if($page=='invoice_return')
        <?php
        if (!isset($invoice_return->id))
            $header = __('xinvoice::invoice.headings.new_credit_note');
        else
            $header = __('xinvoice::invoice.headings.edit_credit_note');
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <a href="javascript:void(0)" class="btn btn-primary"
                           id="invoice_return_save_btn">{{ __('xinvoice::invoice.buttons.save')}}</a>
                        <a href="javascript:void(0)" class="btn btn-primary"
                           id="invoice_return_save_and_new_btn">{{ __('xinvoice::invoice.buttons.save_and_new')}}</a>
                        <a href="javascript:void(0)" class="btn btn-primary"
                           id="invoice_return_update_btn">{{ __('xinvoice::invoice.buttons.update')}}</a>
                        <a href="javascript:void(0)" class="btn btn-primary"
                           id="settle_outstanding_invoices">Settle Outstanding Invoices</a>

                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/invoice_return'}}" method="POST" id="invoice_return_form">
                @csrf
                <input type="hidden" name="invoice_return_id" id="invoice_return_id" value="{{ $invoice_return->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $invoice_return->id ?? '' }}">
                <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $invoice_return->quotation_id ?? '' }}">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $invoice_return->customer_id ?? '' }}">
                <input type="hidden" name="ref_type" id="ref_type" value="INR">
                <input type="hidden" name="isajax" id="isajax" value="{{ Request::ajax() }}">
                <input type="hidden" name="record_product_update_id" id="record_product_update_id">
                <input type="hidden" name="record_payment_update_id" id="record_payment_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="invoice_return_status" id="invoice_return_status" value="{{ $invoice_return->status ?? '' }}">

                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                        <div class="card" id="">

                            <div class="card-body" id="customer_filter">

                                @include('xcustomer::customer_filter')

                                @include('xinvoice::invoice_return.invoice_filter')
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

                        <div class="alert alert-info alert-dismissible" id="customer_info_alert"></div>

                        <div class="alert alert-info alert-dismissible" id="products_info_alert">

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="invoice_return_details_panel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        {{formText(__('xinvoice::invoice.labels.invoice_return_code'), 'invoice_return_no', $invoice_return->invoice_return_code ?? '', array( 'class' => 'form-control ' , 'id' => 'invoice_return_no' , 'readonly' => 'readonly'))}}
                                    </div>
                                    <div class="col-sm-12">
                                 {{formDate(__('xinvoice::invoice.labels.invoice_date'), 'invoice_return_date_created', old('date_created', isset($invoice_return->invoice_return_date) ? $invoice_return->invoice_return_date :Carbon\Carbon::today()->format('Y-m-d')), array( 'class' => 'form-control' , 'id' => 'invoice_return_date_created'))}}
                                    </div>
                                </div>

                                <span id="display_status">
                                    @if(isset($invoice_return->status) && $invoice_return->status=='D')
                                        <span class="label label-danger">Draft</span>
                                    @elseif(isset($invoice_return->status) && $invoice_return->status=='A')
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
                    @include('xinvoice::invoice_return.invoice_return_product_filter')

                    <div class="form-group"></div>


<h5>Invoice return from</h5>
                    <div class="card card-primary" id="invoice_payment_detail_panel">
                        <div class="card-body">
                            <div class="row gutter">
                                <div class="table-responsive">
                                    <table id="PendingInvoiceTable" class="table table-striped table-bordered no-margin"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th></th>

                                            <th>{{ __('xinvoice::invoice.labels.invoice_no')}}</th>
                                            <th>{{ __('xinvoice::invoice.labels.invoice_date')}}</th>
                                            <th>{{ __('xinvoice::invoice.labels.total')}}</th>
                                            <th>{{ __('xinvoice::invoice.labels.paid')}}</th>
                                            <th>{{ __('xinvoice::invoice.labels.balance')}}</th>
                                            <th>Payment Status</th>
                                            <th>{{ __('xinvoice::invoice.labels.status')}}</th>
                                            <th>{{ __('xinvoice::invoice.labels.created_by')}}</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row gutter">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

                            <div class="card">
                                <div class="card-body">
                                        {{formTextArea(__('xinvoice::invoice.labels.remarks'), 'remarks', $invoice_return->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks', 'rows' => 2))}}

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
                                                       value="{{ $invoice_return->sub_total ?? '0.00' }}"
                                                       disabled="true">
                                            </div>
                                        </div>
                                        @if(getConfig('INVOICE_TAX') == 'TRUE')
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_vat" name='checked_vat' value="vat" @if(isset($invoice_return->vat_amount)&& $invoice_return->vat_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_vat">VAT</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="vat_amount" name="vat_amount"
                                                           value="{{ $invoice_return->vat_amount ?? '0' }}">
                                                    <div class="input-group-addon">15%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_nbt" name = 'checked_nbt' value="nbt" @if(isset($invoice_return->nbt_amount)&& $invoice_return->nbt_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_nbt">NBT</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="nbt_amount" name="nbt_amount"
                                                           value="{{ $invoice_return->nbt_amount ?? '0' }}">
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
                                                        <input type="number" class="form-control" id="invoice_return_discount"
                                                               name="invoice_return_discount"
                                                               value="{{ $invoice_return->discount ?? '0' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($invoice_return->discount_type) ? $invoice_return->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'invoice_return_discount_type')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">{{ __('xinvoice::invoice.labels.total')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="total" name="total"
                                                       value="{{ $invoice_return->total ?? '0.00' }}" disabled="true">
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">Refund Amount</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="refund" name="refund"
                                                       value="{{ $invoice_return->refund ?? '0.00' }}" >
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">Credit for the customer</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="customer_credit" name="customer_credit"
                                                       value="{{ $invoice_return->customer_credit ?? '0.00' }}" disabled="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="{{ asset('/pramix/js/invoice_return_js.js?v=10') }}"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {
            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $("#invoice_code_selected").change(function (e) {

                var params = {
                    invoice_id: $(this).val()
                };

                e.preventDefault();
                $.ajax({
                    url: BASE + 'get_invoice_details',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {

                        if (response.status == 'success') {

                            changeCustomer.run(response.invoice.customer_id, '', '', '');
                            if (  $("#ref_id").val()=='')
                            createInvoiceReturnCode.run();
                            $("#invoice_return_item_product_code , #invoice_return_item_category_code , #item_stock_id").find('option').remove();

                            $.each(response.products, function () {
                                $("#invoice_return_item_product_code").append($("<option />").val(this.id).text(this.item_code));
                                $("#item_stock_id").append($("<option />").val(this.id).text(this.stock_id));

                            });

                            $.each(response.categories, function () {
                                $("#invoice_return_item_category_code").append($("<option />").val(this.id).text(this.category_name));
                            });

                            var selectedcategory = new Option('Please select category', '', true, true);
                            $('#invoice_return_item_category_code').append(selectedcategory).trigger('change.select2');

                            var selectedproduct = new Option('Please select product', '', true, true);
                            $('#invoice_return_item_product_code').append(selectedproduct).trigger('change.select2');

                            var selectedstockid = new Option('Select Stock ID', '', true, true);
                            $('#item_stock_id').append(selectedstockid).trigger('change.select2');


                            $('#invoice_return_discount').val(response.invoice.discount);
                            $('#invoice_return_discount_type').val(response.invoice.discount_type).trigger('change');


                        } else {
                            notification(response);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }

                });
                e.preventDefault();
            });

            @if(isset($invoice))
            $('#invoice_code_selected').val({{$invoice->id}}).trigger('change');
                    @endif

            @if(isset($invoice_return->invoice_id))
            $('#invoice_code_selected').val({{$invoice_return->invoice_id}}).trigger('change');
            $("#remarks").Editor("setText", "{{$invoice_return->remarks ?? ''}}")
            $('#overlay').hide('slow');
            @endif

            @if(isset($invoice_return->status) && $invoice_return->status != 'D')
            $('#product-details-card').hide('slow');
            $('#customer_detail_panel :input').prop("disabled", true);
            $('#price_panel :input').prop("disabled", true);
            $('#invoice_return_details_panel :input').prop("disabled", true);
            @endif

        });
    </script>
@endsection

