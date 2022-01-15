@extends('layouts.app')

@section('include_css')

    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>


@endsection



@section('content')
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Inventory</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                </div>
            </div>
        </div>
        <!-- Top bar ends -->
        <div class="main-container">
    <!-- Main content -->
    <div class="row gutter">
        <div class="col-sm-12">
            {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                    {{--<form class="form-inline">--}}

                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" id="searchbusinessname"--}}
                                   {{--placeholder="{{ __('xcustomer::customer.labels.business_name')}}">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" id="searchfullname"--}}
                                   {{--placeholder="{{ __('xcustomer::customer.labels.full_name')}}">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" id="searchmobile"--}}
                                   {{--placeholder="{{ __('xcustomer::customer.labels.mobile')}}">--}}
                        {{--</div>--}}

                    {{--</form>--}}

                {{--</div>--}}
            {{--</div>--}}
            <div class="card card-default">

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="unit_of_consumptions_table" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Stock ID</th>
                                <th>Item Name/Code</th>
                                <th>Store</th>
                                <th>Quantity on Hand</th>
                                <th></th>

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

    </div>
@endsection

@section('include_js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

@endsection


@section('custom_script')


    <script>
        $(document).ready(function () {
            var unit_of_consumptions_table = $('#unit_of_consumptions_table').DataTable({
                processing: true,
                serverSide: true,
                order: [[1, "asc"]],
                'iDisplayLength': 15,
                ajax: BASE + 'get_inventory',

                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'stock_id', name: 'stock_id'},
                    {data: 'product', name: 'product'},
                    {data: 'store', name: 'store', 'bVisible': false},
                    {data: 'qty_on_hand', name: 'qty_on_hand'},
                    {data: 'actions', name: 'actions', 'bVisible': false},

                ]
            });

            $('#unit_of_consumptions_table').editable({
                selector: '.editable_field',
                mode: 'inline',
                ajaxOptions: {
                    type: 'PUT'
                },
                success: function(data) {
                    if (data.status=='success')
                        unit_of_consumptions_table.ajax.reload();
                    else
                        notification(data);
                },
                error: function(errors) {

                }


            });



            $.fn.editableform.buttons =
                '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
                '<i class="fa fa-fw fa-check"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-default btn-sm editable-cancel">' +
                '<i class="fa fa-fw fa-times"></i>' +
                '</button>';







        });

    </script>
@endsection
