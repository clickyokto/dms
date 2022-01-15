@extends('layouts.app')

@section('content')

    @if($page=='invoice')
        <?php
        $header = __('xinvoice::invoice.headings.invoice_list');
        $createLink = 'invoice/create';
        $createText = __('xinvoice::invoice.buttons.new_invoice');
        ?>
    @elseif($page=='estimate')
        <?php
        $header = __('estimate.headings.estimate_list');
        $createLink = 'estimate/create';
        $createText = __('estimate.buttons.new_estimate');
        ?>
    @endif
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <input type="hidden" name="invoice_type" id="invoice_type" value="{{ $invoice_type ?? '' }}">


    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        @if(isset($invoice_type) && $invoice_type=='all')
                            <h4>Orders List</h4>
                        @elseif(isset($invoice_type) && $invoice_type=='quick')
                            <h4>Quick Orders List</h4>
                        @elseif(isset($invoice_type) && $invoice_type=='orders')
                            <h4>Orders List</h4>
                        @elseif(isset($invoice_type) && $invoice_type=='dispatch')
                            <h4>Ready to Dispatch List</h4>
                        @elseif(isset($invoice_type) && $invoice_type=='invoice')
                            <h4>Invoice List</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_INVOICE')
                    <ul class="right-stats" id="mini-nav-right">
                        <a href="{{url('invoice/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                            {{__('xinvoice::invoice.buttons.new_invoice')}}</a>
                    </ul>
                    @endcan
                </div>
            </div>
        </div>
        <!-- Top bar ends -->

        <!-- Main container starts -->
        <div class="main-container">
            <!-- Row starts -->
            <div class="row gutter">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-inline data_list_filters">
                                {{--<div class="form-group">--}}
                                    {{--<input type="text" class="form-control" id="searchinvoiceycode"--}}
                                           {{--placeholder="{{ __('xinvoice::invoice.labels.invoice_no')}}">--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<input type="text" class="form-control" id="searchcustomername"--}}
                                           {{--placeholder="{{ __('xinvoice::invoice.labels.customer')}}">--}}
                                {{--</div>--}}


                                <div class="form-group">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-success active">
                                            <input type="radio" name="payment_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-success">
                                            <input type="radio" name="payment_status" value="Pending"> Pending
                                        </label>
                                        <label class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Payment Status" id="all_payment_status" >
                                            <input type="radio" name="payment_status" value="Partial"> Partial
                                        </label>
                                        <label class="btn btn-success">
                                            <input type="radio" name="payment_status" value="Completed"> Completed
                                        </label>
                                    </div>
                                </div>
                            </div>

                            </div>

                    </div>
                    <div class="table-responsive">
                        <table id="invoiceListTable" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xinvoice::invoice.labels.invoice_no')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.invoice_date')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.customer')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.total')}}</th>
                                    <th>{{ __('xinvoice::invoice.labels.paid')}}</th>
                                <th>Credit</th>
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
            <!-- Row ends -->

        </div>
        <!-- Main container ends -->
    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            $('#all_payment_status').tooltip('show');
            $('#all_status').tooltip('show');

            if ($('#page').val() == 'invoice') {
                var invoiceListTable = $('#invoiceListTable').DataTable({
                    'iDisplayLength': 15,
                    ajax: BASE + 'invoices_list?invoice_type='+$('#invoice_type').val(),
                    order: [[0, "desc"]],
                    processing: true,
                    serverSide: true,
                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'invoice_code', name: 'invoice_code'},
                        {data: 'invoice_date', name: 'invoice_date'},
                        {data: 'customer', name: 'customer'},
                        {data: 'total', name: 'total',className: 'dt-body-right' },
                        {data: 'paid_amount', name: 'paid_amount' ,className: 'dt-body-right'},
                        {data: 'credit', name: 'credit' ,className: 'dt-body-right'},
                        {data: 'balance', name: 'balance' ,className: 'dt-body-right'},
                        {data: 'payment_status', name: 'payment_status'},
                        {data: 'status', name: 'status', 'bVisible': false},
                        {data: 'created_by', name: 'created_by'},
                        {data: 'action', name: 'action'},
                    ]
                });
            }


            $(document).on('click', '.delete_invoice', function (e) {
                var data = invoiceListTable.row($(this).parents('tr')).data();
                var parent = $(this).parents('tr');

                var delete_confirm = $.confirm({
                    title: "Delete Invoice",
                    type: 'red',
                    buttons: {
                        delete: {
                            text: 'Delete',
                            btnClass: 'btn-red',
                            action: function () {

                                e.preventDefault();
                                var params = {

                                };

                                $.ajax({
                                    url: BASE + 'invoice/' + data['id'],
                                    type: 'DELETE',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        if (response.status == 'error')
                                        {
                                            delete_confirm.close();
                                            notification(response);
                                        } else
                                        {

                                            delete_confirm.close();

                                            notification(response);

                                            invoiceListTable
                                                .row(parent)
                                                .remove()
                                                .draw();

                                        }
                                    },
                                    error: function (errors) {

                                    }
                                });
                                e.preventDefault();
                                return false;
                            }
                        },
                        close: function () {
                        }
                    }
                });
            });


            $('#searchinvoiceycode').on('keyup', function () {
                invoiceListTable.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchcustomername').on('keyup', function () {
                invoiceListTable.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=payment_status]').change(function() {
                invoiceListTable.column(8)
                    .search(this.value)
                    .draw();
            });
            $('input[type=radio][name=invoice_status]').change(function() {
                invoiceListTable.column(9)
                    .search(this.value)
                    .draw();
            });
        });
    </script>
@endsection
