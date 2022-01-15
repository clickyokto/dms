@extends('layouts.app')


@section('include_css')
    <!-- Data Tables -->



@endsection

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Configurations</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="mini-nav-right">


                        <a href="{{url('configurations/create')}}" class="btn btn-primary"><i class="fa fa-plus"
                                                                                              aria-hidden="true"></i>
                            {{__('xconfig::config.buttons.new_config')}}</a>

                    </ul>
                </div>
            </div>
        </div>
        <!-- Top bar ends -->

        <!-- Main container starts -->
        <div class="main-container">
            <!-- Row starts -->
            <div class="row gutter">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="table-responsive">
                        <table id="configListTable" class="table table-striped table-bordered no-margin" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Config Name</th>
                                <th>Display Name</th>
                                <th>Category</th>
                                <th>Config Type</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>{{ __('common.labels.action')}}</th>


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

    <script src="{{ asset('/theme_assets/js/jquery_confirm/jquery-confirm.js')}}"></script>


@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {
            $('#configListTable').DataTable({
                processing: true,
                serverSide: true,
                "order": [[0, 'desc']],
                'iDisplayLength': 15,
                ajax: '{!! route('get.all_configs') !!}',

                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'name', name: 'name'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'category', name: 'category'},
                    {data: 'config_type', name: 'config_type'},
                    {data: 'value', name: 'value'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},


                ]
            });


        });
    </script>
@endsection
