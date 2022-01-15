@extends('layouts.app')



@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>User Roles</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                @can('ADD_ROLES')
                <ul class="right-stats" id="mini-nav-right">
                    <a href="{{url('roles/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Role</a>
                </ul>
                @endcan
            </div>
        </div>
    </div>
    <!-- Top bar ends -->


    <!-- Main content -->
    <div class="main-container">
        <div class="row gutter">
            <div class="col-sm-12">
                <div class="card card-bd lobidrag">
                    <div class="card-header">
                        <div class="btn-group">
                            @if (Auth::user()->can(['ADD_USER_ROLES']))
                                {{ Html::link('roles/create', __('xuser::user_role.buttons.new_role'),array('class="btn btn-success"'))}}
                            @endif
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="customerListTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('xuser::user_role.labels.role_name')}}</th>
                                    <th>{{ __('xuser::user_role.labels.role_display_name')}}</th>
                                    <th>{{ __('xuser::user_role.labels.description')}}</th>
                                    <th>{{ __('xuser::user_role.labels.actions')}}</th>
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
    </div>
    <!-- /.content -->




@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {
            $('#customerListTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: '{!! route('get.user_roles') !!}',
                columnDefs: [
                    {className: 'text-center', targets: [4]},
                ],
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'name', name: 'name'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action'},
                ]
            });


        });
    </script>
@endsection
