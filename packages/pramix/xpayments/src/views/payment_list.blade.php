@extends('layouts.app')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                            <h4>{{__('xpayment::payment.headings.payment')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_PAYMENT')
                        <ul class="right-stats" id="mini-nav-right">
                            <a href="{{url('payment/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                {{__('xpayment::payment.buttons.new_payment')}}</a>
                        </ul>
                    @endcan
                      </div>
            </div>
        </div>

        <div class="main-container">
            <div class="row gutter">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-inline data_list_filters">

                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchpaymentycode"
                                           placeholder="{{ __('xpayment::payment.labels.payment_receipt_no')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchcustomername"
                                           placeholder="{{ __('xpayment::payment.labels.payment_customer')}}">
                                </div>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="payment_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="payment_status" value="Completed"> Completed
                                        </label>
                                        <label class="btn btn-primary ">
                                            <input type="radio" name="payment_status" value="Cancelled"> Cancelled
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="payment_list_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>{{ __('xpayment::payment.labels.payment_receipt_no')}}</th>
                                <th>{{ __('xpayment::payment.labels.payment_customer')}}</th>
                                <th>{{ __('xpayment::payment.labels.payment_invoice')}}</th>
                                <th>{{ __('xpayment::payment.labels.customer_mobile')}}</th>
                                <th>{{ __('xpayment::payment.labels.payment_amount')}}</th>
                                <th>{{ __('xpayment::payment.labels.payment_date')}}</th>
                                <th>{{ __('xpayment::payment.labels.payment_status')}}</th>
                                <th>{{ __('common.labels.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {

            $('#all_status').tooltip('show');

                var payment_list_table = $('#payment_list_table').DataTable({
                    'iDisplayLength': 15,
                    ajax: '{!! route('get.all_payments') !!}',
                    order: [[0, "desc"]],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'invoice_id', name: 'invoice_id', 'bVisible': false},
                        {data: 'payment_code', name: 'payment_code'},
                        {data: 'customer', name: 'customer'},
                        {data: 'invoice_code', name: 'invoice_code'},
                        {data: 'customer_mobile', name: 'customer_mobile'},
                        {data: 'payment_amount', name: 'payment_amount'},
                        {data: 'payment_date', name: 'payment_date'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'},
                    ]
                });


            $('#searchpaymentycode').on('keyup', function () {
                payment_list_table.column(2)
                    .search(this.value)
                    .draw();
            });
            $('#searchcustomername').on('keyup', function () {
                payment_list_table.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=payment_status]').change(function() {
                payment_list_table.column(7)
                    .search(this.value)
                    .draw();
            });

            $('#payment_list_table tbody').on('click', 'button.payment-print-button', function (e) {
                var $btn = $(this);
                $btn.button('loading');

                var data = payment_list_table.row($(this).parents('tr')).data();

                var params = {
                    invoice_id: data['invoice_id'],
                    record_payment_id: data['id']
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

            $('#payment_list_table tbody').on('click', 'button.payment-view-button', function (e) {

                var data = payment_list_table.row($(this).parents('tr')).data();

                    window.payment_model = $.confirm({
                        title: '',
                        draggable: true,
                        boxWidth: '80%',
                        closeIcon: true,
                        useBootstrap: false,
                        buttons: {

                            close: function () {
                            }
                        },
                        content: 'url:' + BASE + 'payment/view/' + data['id'],
                        onContentReady: function () {

                        },
                        columnClass: 'medium',
                    });

                return false;

            });


            });
    </script>
@endsection
