@extends('layouts.app')

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Email List</h4>

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats">
                        @can('SEND_EMAIL')
                            <a href="{{url('email_sender/create')}}" class="btn btn-default"><i class="fa fa-plus"
                                                                                                aria-hidden="true"></i>
                                Email Sender</a>
                        @endcan
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
                        <table id="mailListTable" class="table table-striped table-bordered no-margin" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Ref Type</th>
                                <th>Ref ID</th>
                                <th>Mail</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
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
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')


@endsection

@section('include_css')

@endsection

@section('custom_script')

    <script>
        $(document).ready(function () {

            $('#all_status').tooltip('show');
            var mailListTable  = $('#mailListTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: '{!! route('get.all_emails')!!}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'ref_type', name: 'ref_type'},
                    {data: 'ref_id', name: 'ref_id'},
                    {data: 'mail_add', name: 'mail_add'},
                    {data: 'subject', name: 'subject'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},

                ]
            });

            $('#searchporcode').on('keyup', function () {
                grn_list_table.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchsuppliername').on('keyup', function () {
                grn_list_table.column(3)
                    .search(this.value)
                    .draw();
            });

            $('input[type=radio][name=por_status]').change(function() {
                grn_list_table.column(4)
                    .search(this.value)
                    .draw();
            });
        });
    </script>
@endsection
