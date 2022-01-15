@extends('layouts.app')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                            <h4>Cheque List</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_CHEQUE')
                        <ul class="right-stats" id="mini-nav-right">
                            <a href="{{url('cheque/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                Add Cheque</a>
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
                                           placeholder="Cheque #">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchcustomername"
                                           placeholder="Payer">
                                </div>
                                {{--                                @if(Auth::user()->can('VIEW_ALL_PLANTS_RECORDS'))--}}
                                <div class="form-group">
                                    {{ Form::select('bank', getConfig('BANKS'), '' , array('class' => 'form-control select2', 'id' => 'bank','data-toggle'=> 'tooltip', 'data-placement' => 'top', 'title'=>'Bank')) }}

                                </div>
                                {{--                                @endif--}}
                            </div>

                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="payment_list_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Bank Name</th>
                                <th>Cheque #</th>
                                <th>Payer</th>
                                <th>Cheque Date</th>
                                <th>Cash Cheque</th>
                                <th>Amount</th>
                                <th>Remarks</th>
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
                    ajax: '{!! route('get.all_cheques') !!}',
                    order: [[0, "desc"]],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'bank_name', name: 'bank_name'},
                        {data: 'cheque_no', name: 'cheque_no'},
                        {data: 'payer_name', name: 'payer_name'},
                        {data: 'cheque_date', name: 'cheque_date'},
                        {data: 'cash_cheque', name: 'cash_cheque'},
                        {data: 'amount', name: 'amount'},
                        {data: 'remarks', name: 'remarks'},
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

            $('#bank').change(function() {

                if($(this).val() == '')
                {
                    payment_list_table.column(1)
                        .search('')
                        .draw();
                }else{

                    payment_list_table.column(1)
                        .search($(this).val())
                        .draw();
                }
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

            // $('#payment_list_table tbody').on('click', 'button.cheque-edit-button', function (e) {
            //
            //     var data = payment_list_table.row($(this).parents('tr')).data();
            //
            //     $.ajax({
            //         url: BASE + 'payment/' + data['id']+'/edit',
            //         type: 'POST',
            //         dataType: 'JSON',
            //         data: $.param(params),
            //         success: function (response) {
            //
            //         },
            //         error: function (xhr, ajaxOptions, thrownError) {
            //
            //             notificationError(xhr, ajaxOptions, thrownError);
            //         }
            //     });
            //
            // });


            });
    </script>
@endsection
