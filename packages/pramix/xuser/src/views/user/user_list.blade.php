@extends('layouts.app')


@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>System Users</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                @can('ADD_USERS')
                <ul class="right-stats" id="mini-nav-right">

                    <a href="{{url('users/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New User</a>
                </ul>
                @endcan
            </div>
        </div>
        </div>
    </div>
    <!-- Top bar ends -->

<!-- Main content -->
<div class="main-container">
    <div class="row gutter">
        <div class="col-sm-12">
            <div class="card card-bd lobidrag">

                <div class="card-body">

                    <div class="table-responsive">
                    <table id="customerListTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xuser::user.labels.user_name')}}</th>
                                <th>{{ __('xuser::user.labels.name')}}</th>
                                <th>{{ __('xuser::user.labels.email')}}</th>
                                <th>{{ __('xuser::user.labels.role')}}</th>
                                <th>{{ __('xuser::user.labels.status')}}</th>
                                <th >{{ __('xuser::user.labels.actions')}}</th>
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
</div> <!-- /.content -->
@endsection


@section('custom_script')
<script>
$(document).ready(function () {
    var customerListTable = $('#customerListTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        'iDisplayLength': 15,
        ajax: '{!! route('get.users') !!}',
        columnDefs: [
            {className: 'text-center', targets: [6]},
        ],
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'username', name: 'username'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });


    $(document).on('click', '#change_user_status', function (e) {
        var data = customerListTable.row($(this).parents('tr')).data();
        var parent = $(this).parents('tr');
        var rowidx = customerListTable.row(parent).index();
        e.preventDefault();
        var params = {
            user_id: data['id']
        };

        $.ajax({
            url: BASE + 'user/change_status',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'error')
                {

                } else
                {
                    customerListTable.cell(rowidx, 5).data(response.user_status).draw();

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;
    });


    $(document).on('click', '#delete_user', function (e) {
        var data = customerListTable.row($(this).parents('tr')).data();
        var parent = $(this).parents('tr');
        var rowidx = customerListTable.row(parent).index();
        var delete_confirm = $.confirm({
            title: 'Delete User Confirmation',
            type: 'red',
            buttons: {
                delete: {
                    text: 'Delete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        e.preventDefault();
                        var params = {

                        };

                        $.ajax({
                            url: BASE + 'users/' + data['id'],
                            type: 'DELETE',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'error')
                                {

                                } else
                                {
                                    customerListTable
                                            .row(parent)
                                            .remove()
                                            .draw();
                                    delete_confirm.close()

                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                notificationError(xhr, ajaxOptions, thrownError);
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

});
</script>
@endsection
