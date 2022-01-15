@extends('layouts.app')

@section('content')

    @if($page=='purchase_order')
        <?php
        $header = __('xpurchase_order::purchase_order.headings.purchase_order_list');
        $createLink = 'purchase_order/create';
        $createText = __('xpurchase_order::purchase_order.buttons.new_purchase_order');
        ?>
    @elseif($page=='estimate')
        <?php
        $header = __('estimate.headings.estimate_list');
        $createLink = 'estimate/create';
        $createText = __('estimate.buttons.new_estimate');
        ?>
    @endif
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        @if($page=='purchase_order')
                            <h4>{{$header}}</h4>
                        @elseif($page=='estimate')
                            <h4>{{$header}}</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_PURCHASE_ORDER')
                    <ul class="right-stats" id="mini-nav-right">
                        <a href="{{url('purchase_order/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                            {{__('xpurchase_order::purchase_order.buttons.new_purchase_order')}}</a>
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
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchpurchase_orderycode"
                                           placeholder="{{ __('xpurchase_order::purchase_order.labels.purchase_order_no')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchsuppliername"
                                           placeholder="Supplier">
                                </div>

                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="purchase_order_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="purchase_order_status" value="Draft"> Draft
                                        </label>
                                        <label class="btn btn-primary ">
                                            <input type="radio" name="purchase_order_status" value="Completed"> Completed
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons" >
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
                        <table id="purchase_orderListTable" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xpurchase_order::purchase_order.labels.purchase_order_no')}}</th>
                                <th>{{ __('xpurchase_order::purchase_order.labels.purchase_order_date')}}</th>
                                <th>Suppiler</th>
                                <th>{{ __('xpurchase_order::purchase_order.labels.total')}}</th>
                                    <th>{{ __('xpurchase_order::purchase_order.labels.paid')}}</th>
                                    <th>{{ __('xpurchase_order::purchase_order.labels.balance')}}</th>
                                <th>Payment Status</th>
                                    <th>{{ __('xpurchase_order::purchase_order.labels.status')}}</th>
                                <th>{{ __('xpurchase_order::purchase_order.labels.created_by')}}</th>
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

            if ($('#page').val() == 'purchase_order') {
                var purchase_orderListTable = $('#purchase_orderListTable').DataTable({
                    'iDisplayLength': 15,
                    ajax: '{!! route('get.purchase_orders') !!}',
                    order: [[0, "desc"]],
                    processing: true,
                    serverSide: true,
                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'purchase_order_code', name: 'purchase_order_code'},
                        {data: 'purchase_order_date', name: 'purchase_order_date'},
                        {data: 'supplier', name: 'supplier'},
                        {data: 'total', name: 'total',className: 'dt-body-right' },
                        {data: 'paid_amount', name: 'paid_amount' ,className: 'dt-body-right'},
                        {data: 'balance', name: 'balance' ,className: 'dt-body-right'},
                        {data: 'payment_status', name: 'payment_status'},
                        {data: 'status', name: 'status'},
                        {data: 'created_by', name: 'created_by'},
                        {data: 'action', name: 'action'},
                    ]
                });
            }

            $('#searchpurchase_orderycode').on('keyup', function () {
                purchase_orderListTable.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchsuppliername').on('keyup', function () {
                purchase_orderListTable.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=payment_status]').change(function() {
                purchase_orderListTable.column(7)
                    .search(this.value)
                    .draw();
            });
            $('input[type=radio][name=purchase_order_status]').change(function() {
                purchase_orderListTable.column(8)
                    .search(this.value)
                    .draw();
            });
        });
    </script>
@endsection
