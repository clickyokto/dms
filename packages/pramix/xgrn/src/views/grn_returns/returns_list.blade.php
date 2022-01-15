@extends('layouts.app')

@section('content')
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{__('xgrn::grn.headings.grn_return_list')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_GRN_RETURN')
                    <ul class="right-stats" id="mini-nav-right">

                        <a href="{{url('grn_return/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                            {{__('xgrn::grn.buttons.new_grn_return')}}</a>

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
                                    <input type="text" class="form-control" id="searchporcode"
                                           placeholder="{{ __('xgrn::grn.labels.grn_return_code')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchsuppliername"
                                           placeholder="{{ __('xgrn::grn.labels.supplier')}}">
                                </div>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons" >
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="por_status" value="" checked> All
                                        </label>
                                        <label class="btn btn-primary" id="all_status" data-toggle="tooltip" data-placement="top" title="Status">
                                            <input type="radio" name="por_status" value="Draft"> Draft
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="radio" name="por_status" value="Approved"> Approved
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="table-responsive">
                        <table id="grn_return_list_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xgrn::grn.labels.grn_return_code')}}</th>
                                <th>{{ __('xgrn::grn.labels.order_date')}}</th>
                                <th>{{ __('xgrn::grn.labels.supplier')}}</th>
                                <th>{{ __('xgrn::grn.labels.total')}}</th>
                                <th>{{ __('xgrn::grn.labels.status')}}</th>
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
            var grn_return_list_table = $('#grn_return_list_table').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: '{!! route('get.grn_return') !!}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'grn_return_code', name: 'grn_return_code'},
                    {data: 'grn_return_date', name: 'grn_return_date'},
                    {data: 'supplier', name: 'supplier'},
                    {data: 'total', name: 'total', className: 'dt-body-right'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });

            $('#searchporcode').on('keyup', function () {
                grn_return_list_table.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchsuppliername').on('keyup', function () {
                grn_return_list_table.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=por_status]').change(function() {
                grn_return_list_table.column(5)
                    .search(this.value)
                    .draw();
            });
        });
    </script>
@endsection
