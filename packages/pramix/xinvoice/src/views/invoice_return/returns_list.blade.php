@extends('layouts.app')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                            <h4>{{__('xinvoice::invoice.headings.credit_note')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_CREDIT_NOTE')
                    <ul class="right-stats" id="mini-nav-right">

                        <button class="btn btn-success" id="select_invoice_product_btn">Quick Search Product</button>

                                           <a href="{{url('invoice_return/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                            {{__('xinvoice::invoice.buttons.create_credit_note')}}</a>

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
                                    <input type="text" class="form-control" id="searchinvoiceycode"
                                           placeholder="Return #">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchcustomername"
                                           placeholder="{{ __('xinvoice::invoice.labels.customer')}}">
                                </div>
                                <div class="form-group">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="invoice_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="invoice_status" value="Draft"> Draft
                                        </label>
                                        <label class="btn btn-primary ">
                                            <input type="radio" name="invoice_status" value="Completed"> Completed
                                        </label>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="invoice_return_list_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xinvoice::invoice.labels.invoice_return_code')}}</th>
                                <th>Return Date</th>
                                <th>{{ __('xinvoice::invoice.labels.customer')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.total')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.status')}}</th>
                                <th>{{ __('xinvoice::invoice.labels.created_by')}}</th>
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

                var invoice_return_list_table = $('#invoice_return_list_table').DataTable({
                    'iDisplayLength': 15,
                    ajax: '{!! route('get.invoice_return') !!}',
                    order: [[0, "desc"]],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'invoice_return_code', name: 'invoice_return_code'},
                        {data: 'invoice_return_date', name: 'invoice_return_date'},
                        {data: 'customer', name: 'customer'},
                        {data: 'total', name: 'total',className: 'dt-body-right' },
                        {data: 'status', name: 'status'},
                        {data: 'created_by', name: 'created_by'},
                        {data: 'action', name: 'action'},
                    ]
                });


            $('#searchinvoiceycode').on('keyup', function () {
                invoice_return_list_table.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchcustomername').on('keyup', function () {
                invoice_return_list_table.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=invoice_status]').change(function() {
                invoice_return_list_table.column(5)
                    .search(this.value)
                    .draw();
            });

            });



    </script>
@endsection
