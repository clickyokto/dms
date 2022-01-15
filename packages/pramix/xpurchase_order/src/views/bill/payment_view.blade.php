@extends('layouts.model')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <input type="hidden" name="payment_id" id="payment_id" value="{{ $payments->id ?? '' }}">
    <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice->id ?? '' }}">
    <input type="hidden" name="ref_type" id="ref_type" value="PAY">
    <input type="hidden" name="cancel_amount" id="cancel_amount" value="">


    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{__('xpayment::payment.headings.payment_view')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        @can('DELETE_PAYMENT')
                        @if($payments->status==1)
                            <a href="javascript:void(0)" class="btn btn-danger"
                               id="payment_cancel">{{ __('xpayment::payment.labels.payment_cancel')}}</a>
                        @endif
                        @endcan
                        <a href="javascript:void(0)" class="btn btn-success"
                           id="payment_print">{{ __('xpayment::payment.labels.payment_print')}}</a>

                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">

            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card" id="customer_detail_panel">
                        <div class="card-header">
                            <h4>{{ __('xcustomer::customer.headings.panel_customer_details')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.customer_code'), 'customer_code', $customer->business_name ?? '', array( 'class' => 'form-control ' , 'id' => 'customer_code' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.customer_name'), 'customer_name', $customer->fname.' '.$customer->lname ?? '', array( 'class' => 'form-control ' , 'id' => 'customer_name' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.customer_mobile'), 'customer_mobile', $customer->mobile ?? '', array( 'class' => 'form-control ' , 'id' => 'customer_mobile' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">

                                    {{formText(__('xpayment::payment.labels.customer_email'), 'customer_email', $customer->email ?? '', array( 'class' => 'form-control ' , 'id' => 'customer_email' , 'readonly' => 'readonly'))}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card" id="payment_details_panel">
                        <div class="card-header">
                            <h4>{{ __('xpayment::payment.headings.panel_payment_details')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.invoice_code'), 'invoice_code', $invoice->invoice_code ?? '', array( 'class' => 'form-control ' , 'id' => 'invoice_code' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.payment_code'), 'payment_code', $payments->payment_code ?? '', array( 'class' => 'form-control ' , 'id' => 'payment_code' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formDate(__('xpayment::payment.labels.payment_date'), 'payment_date_created', isset($invoice->invoice_date) ? $invoice->invoice_date :Carbon\Carbon::today()->format('Y-m-d'), array( 'class' => 'form-control' , 'id' => 'payment_date_created' ,  'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.created_by'), 'created_by',$user->username ?? '', array('class'=> 'form-control', 'id' => 'created_by' ,  'readonly' => 'readonly'))}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card" id="payment_details_panel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.payment_method'), 'payment_method', $payments->payment_method ?? '', array( 'class' => 'form-control ' , 'id' => 'payment_method' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.payment_ref'), 'payment_ref', $payments->payment_ref_no ?? '', array( 'class' => 'form-control ' , 'id' => 'payment_ref' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.payment_remarks'), 'payment_remarks', $payments->payment_remarks ?? '', array( 'class' => 'form-control ' , 'id' => 'payment_remarks' , 'readonly' => 'readonly'))}}
                                </div>
                                <div class="col-sm-6">
                                    {{formText(__('xpayment::payment.labels.payment_amount'), 'payment_amount', $payments->payment_amount ?? '', array( 'class' => 'form-control ' , 'id' => 'payment_amount' , 'readonly' => 'readonly'))}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {

            $('#payment_cancel').click(function (e) {

                var invoice_save_confirm = $.confirm({
                    title: 'Delete payment',
                    type: 'blue',
                    buttons: {
                        draft: {
                            text: 'Cancel',
                            keys: ['shift', 'alt'],
                            btnClass: 'btn-default',
                            action: function () {

                            }
                        },
                        complete: {
                            text: 'Delete',
                            keys: ['shift', 'alt'],
                            btnClass: 'btn-danger',
                            action: function () {

                                var payment_details = $('#payment-details-card-form :input').serialize();

                                var params = {
                                    payment_details: payment_details,
                                    invoice_id: $('#invoice_id').val(),
                                    record_payment_id: $("#payment_id").val()
                                };
                                $.ajax({
                                    url: BASE + 'invoice/delete_payment',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        notification(response);
                                        if (response.status == 'success') {
                                            $("#cancel_amount").val($("#payment_amount").val())


                                            calPrice.run()

                                            window.location.href = BASE + 'payment';
                                        } else {

                                            return false;
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {

                                        notificationError(xhr, ajaxOptions, thrownError);
                                    }
                                });

                            }
                        },

                    }
                });

            });

            $('#payment_print').click(function (e) {

                var $btn = $(this);
                $btn.button('loading');

                var params = {
                    invoice_id: $('#invoice_id').val(),
                    record_payment_id: $("#payment_id").val()

                };

                $.ajax({
                    url: BASE + 'invoice/print_payment',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {
                            window.open(response.url);
                            $btn.button('reset');

                        } else {
                            notification(response);
                            $btn.button('reset');
                            return false;
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });

                return false;
            });

        });
    </script>
@endsection
