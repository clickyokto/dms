@extends('layouts.app')



@section('include_css')

@endsection

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Areas</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_PRODUCTS')
                        <ul class="right-stats" id="mini-nav-right">
                            <a href="{{url('areas/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                Add Area</a>
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

                    <div class="table-responsive">
                        <table id="areas_list_table" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
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
            var areas_list_table  =$('#areas_list_table').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 10,
                ajax: BASE + 'get_areas_list',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action'},
                ]
            });


            $(document).on('click', '.delete_area', function (e) {
                var data = areas_list_table.row($(this).parents('tr')).data();
                var parent = $(this).parents('tr');

                var delete_confirm = $.confirm({
                    title: "Delete Area",
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
                                    url: BASE + 'areas/' + data['id'],
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

                                            areas_list_table
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




        });
    </script>
@endsection