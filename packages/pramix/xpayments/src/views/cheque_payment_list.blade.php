@extends('layouts.app')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                            <h4>Cheques List</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

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
                                    <input type="text" class="form-control" id="searchcustomername"
                                           placeholder="{{ __('xpayment::payment.labels.payment_customer')}}">
                                </div>
                                <div class="form-group">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="payment_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="payment_status" value="Pending"> Pending
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="payment_status" value="Cleared"> Cleared
                                        </label>
                                        <label class="btn btn-primary ">
                                            <input type="radio" name="payment_status" value="REJECT"> Rejected
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
                                <th>Customer</th>
                                <th>Invoice</th>
                                <th>Received Date</th>
                                <th>Cheque Date</th>
                                <th>{{ __('xpayment::payment.labels.payment_amount')}}</th>
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

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $('#all_status').tooltip('show');

                var payment_list_table = $('#payment_list_table').DataTable({
                    'iDisplayLength': 15,
                    ajax: '{!! route('get.all_cheque_payments') !!}',
                    order: [[0, "desc"]],
                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'customer', name: 'customer'},
                        {data: 'invoice', name: 'invoice'},
                        {data: 'payment_date', name: 'payment_date'},
                        {data: 'cheque_date', name: 'cheque_date'},
                        {data: 'payment_amount', name: 'payment_amount'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'},
                    ]
                });



            $('#searchcustomername').on('keyup', function () {
                payment_list_table.column(1)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=payment_status]').change(function() {
                payment_list_table.column(6)
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

            $('#payment_list_table tbody').on('click', 'button.payment-edit-button', function (e) {

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

            })

            $('#payment_list_table tbody').on('click', 'button.payment-delete-button', function (e) {
                var data = payment_list_table.row($(this).parents('tr')).data();
                $.confirm({
                    title: 'Cheque Reject!',
                    content: 'Are you sure?!',
                    buttons: {
                        cancel: function () {
                        },
                        delete: {
                            text: 'Reject',
                            btnClass: 'btn-danger',
                            keys: ['enter', 'shift'],
                            action: function(){


                                var payment_details = $('#payment_list_table :input').serialize();

                                var params = {
                                    cheque_id: data['id']
                                };
                                $.ajax({
                                    url: BASE + 'payment/reject_payment',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        if (response.status == 'success') {
                                            payment_list_table.ajax.reload();
                                        } else {
                                            notification(response);
                                            return false;
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {

                                        notificationError(xhr, ajaxOptions, thrownError);
                                    }
                                });

                            }
                        }
                    }
                });


            });

            $('#payment_list_table tbody').on('click', 'button.payment-approve-button', function (e) {
                var data = payment_list_table.row($(this).parents('tr')).data();
                $.confirm({
                    title: 'Cheque Approve!',
                    content: 'Are you sure?',
                    buttons: {
                        cancel: function () {

                        },
                        approve: {
                            text: 'Approve',
                            btnClass: 'btn-success',
                            keys: ['enter', 'shift'],
                            action: function(){


                                var params = {
                                    cheque_id: data['id']
                                };
                                $.ajax({
                                    url: BASE + 'payment/approve_payment',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        if (response.status == 'success') {
                                            payment_list_table.ajax.reload();
                                        } else {
                                            notification(response);
                                            return false;
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        notificationError(xhr, ajaxOptions, thrownError);
                                    }
                                });

                            }
                        }
                    }
                });


            });


            });
    </script>
@endsection
