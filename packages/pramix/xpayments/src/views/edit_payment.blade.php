@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css"
          rel="stylesheet"/>

@endsection
@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Edit Payment {{$payment->payment_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                                id="payment_update_btn">{{ __('xinvoice::invoice.buttons.update')}}</button>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/payment'}}" method="POST" id="payment_form">
                @csrf
                <input type="hidden" name="payment_id" id="payment_id" value="{{$payment->id ?? ''}}">
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice->id ?? ''}}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $payment->id ?? ''}}">
                <input type="hidden" name="ref_type" id="ref_type" value="IPE">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="payment_status" id="payment_status" value="{{ $payment->status ?? '' }}">

                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="card" id="customer-details-card">
                            <div class="card-header">
                                <h4>Customer Details</h4>
                            </div>

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Customer Name</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="text-blue">{{$customer->fname}} {{$customer->lname}}</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Invoice Code</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="text-blue">{{$invoice->invoice_code}}</label>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="card" id="payment-details-card">
                            <div class="card-header">
                                <h4>Payment Details</h4>
                            </div>

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        {{formDate('Create Date', 'date', $payment->payment_date ?? \Carbon\Carbon::today(), array( 'class' => 'form-control' , 'id' => 'date'))}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Payment Type</label>
                                            <select class="form-control" id="payment_method_selected"
                                                    name="payment_method_selected">
                                                <option value="cash">Cash</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="credit">Over payments</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            {{--                                    <label>Cheque Date</label>--}}
                                            {{--                                    <input type="date" class="form-control"--}}
                                            {{--                                           id="cheque_date" name="cheque_date"--}}
                                            {{--                                           placeholder="Cheque Date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}">--}}
                                            {{formDate('Cheque Date', 'cheque_date', $payment->cheque_date ?? \Carbon\Carbon::today(), array( 'class' => 'form-control' , 'id' => 'cheque_date'))}}

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="cheque_bank_col">
                                        {{formDropdown('Bank', 'cheque_bank',getConfig('BANKS_LIST'), $payment->bank_id ?? '', array('class' => 'form-control', 'id' => 'cheque_bank'))}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Ref No/ Cheque No</label>
                                            <input type="text" class="form-control" id="payment_ref_no"
                                                   name="payment_ref_no" value="{{$payment->payment_ref_no ?? ''}}"
                                                   placeholder="{{ __('xinvoice::invoice.labels.ref_no')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <input type="text" class="form-control" id="payment_remarks"
                                                   name="payment_remarks" value="{{$payment->payment_remarks ?? ''}}"
                                                   placeholder="{{ __('xinvoice::invoice.labels.remarks')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" class="form-control"
                                                   id="payment_amount" name="payment_amount"
                                                   value="{{$payment->payment_amount ?? ''}}"
                                                   placeholder="{{ __('xinvoice::invoice.labels.amount')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/payment_js.js') }}"></script>
    <script type="text/javascript"
            src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function () {
                return "Are you sure you want to leave?";
            };



            $("#payment_method_selected").change(function (e) {
                var btn_id = $("#payment_method_selected").val();
                if ($("#payment_method_selected").val() == 'cheque') {

                    $("#cheque_bank_col").show('slow')
                } else {

                    $("#cheque_bank_col").hide('slow');
                }
                if ($("#payment_method_selected").val() == '') {
                    $("#payment_amount").val('');
                    return false;
                }
                e.preventDefault();
                return false;
            });

            $('#payment_method_selected').val('{{$payment->payment_method ?? ''}}').trigger('change');
            @if(isset($payment->payment_method) && $payment->payment_method == 'cheque')
            $("#cheque_bank_col").show('slow')

            @endif


            $("#payment_update_btn").click(function (e) {
                var valid = $("#payment_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                var cheque_save_confirm = $.confirm({
                    title: 'Update Payment',
                    type: 'blue',
                    buttons: {
                        draft: {
                            text: 'Update',
                            btnClass: 'btn-primary',
                            action: function () {

                                var payment_details = $('#payment_form').serialize();

                                var params = {
                                    payment_details: payment_details,
                                    payment_id: $('#payment_id').val(),
                                };

                                $.ajax({
                                    url: BASE + 'payment_edit',
                                    type: 'POST',
                                    async: false,
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        notification(response);
                                        if (response.status == 'success') {
                                            setTimeout(
                                                function () {
                                                    window.location.href = BASE + 'payment';
                                                }, 1000);
                                        } else
                                        {


                                        }

                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {

                                        notificationError(xhr, ajaxOptions, thrownError);
                                    }
                                });
                            }
                        },
                        complete: {
                            text: 'Cancel',
                            action: function () {

                            }
                        },

                    }
                });
                return false;


            });
        });
    </script>
@endsection

