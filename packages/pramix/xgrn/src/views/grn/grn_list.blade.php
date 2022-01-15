@extends('layouts.app')

@section('content')

    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{__('xgrn::grn.headings.grn_list')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="mini-nav-right">
                        @can('ADD_GRN')
                        <a href="{{url('grn/create')}}" class="btn btn-primary"><i class="fa fa-plus"
                                                                                   aria-hidden="true"></i>
                            {{__('xgrn::grn.buttons.create_grn')}}</a>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="row gutter">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                    <div class="table-responsive">
                        <table id="orderListTable" class="table table-striped table-bordered no-margin" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xgrn::grn.labels.grn_code')}}</th>
                                <th>{{ __('xgrn::grn.labels.order_date')}}</th>
                                <th>user</th>
                                <th>{{ __('xgrn::grn.labels.status')}}</th>
                                <th>{{ __('xgrn::grn.labels.actions')}}</th>
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

@section('include_js')

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            $('#all_status').tooltip('show');
            var grn_list_table  = $('#orderListTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: '{!! route('get.grn')!!}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'grn_code', name: 'grn_code'},
                    {data: 'grn_date', name: 'grn_date'},
                    {data: 'user', name: 'user'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });



            $(document).on('click', '.delete_grn', function (e) {
                var data = grn_list_table.row($(this).parents('tr')).data();
                var parent = $(this).parents('tr');

                var delete_confirm = $.confirm({
                    title: "Delete GRN",
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
                                    url: BASE + 'grn/' + data['id'],
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

                                            grn_list_table
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
