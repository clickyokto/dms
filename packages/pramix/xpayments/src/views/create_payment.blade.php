@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />

@endsection
@section('content')
    @if($page=='payment')
        <?php
        if (!isset($payment->id))
            $header = __('xpayment::payment.headings.new_payment');
        else
            $header = __('xpayment::payment.headings.edit_payment');
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}} {{$payment->payment_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/payment'}}" method="POST" id="payment_form">
                @csrf
                <input type="hidden" name="payment_id" id="payment_id" value="{{ $payment->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $payment->id ?? '' }}">
                <input type="hidden" name="ref_type" id="ref_type" value="CPM">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $payment->customer_id ?? $customer_id }}">
                <input type="hidden" name="page" id="ref_type" value="PM">
                <input type="hidden" name="record_payment_update_id" id="record_payment_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="payment_status" id="payment_status" value="{{ $payment->status ?? '' }}">
                <input type="hidden" name="invoice_id" id="invoice_id" value="">


                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="customer_detail_panel">
                            <div class="card-body">
                                @include('xcustomer::customer_filter')

                            </div>
                        </div>
                        <div class="alert alert-info alert-dismissible" id="customer_info_alert"></div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" >
                        @include('xinvoice::payment_filter')

                    </div>
                </div>
                <!-- Row ends -->
                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    @include('xpayment::payment_filter')
                    <div class="form-group">
                    </div>

                    <!-- Row starts -->
                    <div class="row gutter">

                    </div>
                </div>
            </form>


        </div>
        <!-- Main container ends -->

    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/payment_js.js') }}"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

         if($('#customer_id').val() != '') {
             $('#customer_id_selected').val($('#customer_id').val()).trigger('change.select2');
             changeCustomer.run($("#customer_id").val(), '', '');
             customer_outstanding_table.ajax.url(BASE + 'invoices_list/' + $("#customer_id").val() + '/customer/payment').load();
             InvoicePaymentsTable.ajax.url(BASE + 'invoice/get_sales_payments/' + $("#customer_id").val()+'/'+ 'customer').load();
         }

        });
    </script>
@endsection

