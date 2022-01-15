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
                        <h4> {{__('xconfig::config.headings.config_category_list')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="mini-nav-right">


                        <a href="{{url('config_categories/create')}}" class="btn btn-primary"><i class="fa fa-plus"
                                                                                              aria-hidden="true"></i>
                            {{__('xconfig::config.buttons.new_config_category')}}</a>

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
                        <table id="config_categories_list_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Category Name</th>
                                <th>Description</th>
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
            var config_categories_list_table = $('#config_categories_list_table').DataTable({
                processing: true,
                serverSide: true,
                "order": [[0, 'desc']],
                'iDisplayLength': 15,
                ajax: '{!! route('get.all_config_categories') !!}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},


                ]
            });


        });
    </script>
@endsection
