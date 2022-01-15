@extends('layouts.app')

@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{ __('xcustomer::customer.headings.all_customer_list')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @can('ADD_CUSTOMER')
                        <ul class="right-stats" id="mini-nav-right">
                            <a href="{{url('customer/create')}}"
                               class="btn btn-gradient-primary waves-effect waves-light float-right mb-3"><i
                                        class="fa fa-plus"
                                        aria-hidden="true"></i>
                                {{__('xcustomer::customer.buttons.new_customer')}}</a>
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
                            <form class="form-inline">

                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchbusinessname"
                                           placeholder="{{ __('xcustomer::customer.labels.business_name')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchfullname"
                                           placeholder="{{ __('xcustomer::customer.labels.full_name')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchmobile"
                                           placeholder="{{ __('xcustomer::customer.labels.mobile')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchtelephone"
                                           placeholder="{{ __('xcustomer::customer.labels.telephone')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchemail"
                                           placeholder="{{ __('xcustomer::customer.labels.email')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchnic"
                                           placeholder="{{ __('xcustomer::customer.labels.nic')}}">
                                </div>
                                {{formDropdown('', 'customer_city',\Pramix\XGeneral\Models\CityModel::pluck('name_en', 'id'),'', array('class' => 'form-control select2', 'id' => 'customer_city'))}}

                                @php
                                    $roles = \Pramix\XUser\Models\User::role('REPRESENTATIVE')->pluck('username','id');
                                @endphp

                                {{formDropdown('', 'rep',$roles,'', array('class' => 'form-control ', 'id' => 'rep'))}}

                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="customerListTable" class="table table-striped table-bordered no-margin"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xcustomer::customer.labels.business_name')}}</th>
                                <th>{{ __('xcustomer::customer.labels.full_name')}}</th>
                                <th>{{ __('xcustomer::customer.labels.mobile')}}</th>
                                <th>{{ __('xcustomer::customer.labels.telephone')}}</th>
                                <th>{{ __('xcustomer::customer.labels.email')}}</th>
                                <th>{{ __('xcustomer::customer.labels.nic')}}</th>
                                <th>Area</th>
                                <th>Rep</th>
                                <th>{{ __('xcustomer::customer.labels.outstanding_amount')}}</th>
                                <th>{{ __('xcustomer::customer.labels.action')}}</th>
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
            var customer_list_table = $('#customerListTable').DataTable({
                processing: true,
                serverSide: true,
                "order": [[0, 'desc']],
                'iDisplayLength': 15,
                ajax: '{!! route('get.all_customers') !!}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'business_name', name: 'business_name'},
                    {data: 'fullname', name: 'fullname'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'telephone', name: 'telephone', 'bVisible': false},
                    {data: 'email', name: 'email', 'bVisible': false},
                    {data: 'nic', name: 'nic', 'bVisible': false},
                    {data: 'city', name: 'city'},
                    {data: 'rep', name: 'rep'},
                    {data: 'outstanding_amount', name: 'outstanding_amount', className: 'dt-body-right'},
                    {data: 'action', name: 'action'},


                ]
            });

            $('#searchbusinessname').on('keyup', function () {
                customer_list_table.column(1)
                    .search(this.value)
                    .draw();
            });
            $('#searchfullname').on('keyup', function () {
                customer_list_table.column(2)
                    .search(this.value)
                    .draw();
            });
            $('#searchmobile').on('keyup', function () {
                customer_list_table.column(3)
                    .search(this.value)
                    .draw();
            });
            $('#searchtelephone').on('keyup', function () {
                customer_list_table.column(4)
                    .search(this.value)
                    .draw();
            });
            $('#searchemail').on('keyup', function () {
                customer_list_table.column(5)
                    .search(this.value)
                    .draw();
            });

            $('#searchnic').on('keyup', function () {
                customer_list_table.column(6)
                    .search(this.value)
                    .draw();
            });

            $('#searchaccountno').on('keyup', function () {
                customer_list_table.column(7)
                    .search(this.value)
                    .draw();
            });


            var select_city = new Option('Select city', '', true, true);
            $('#customer_city').append(select_city).trigger('change');

            var select_rep = new Option('Select Rep', '', true, true);
            $('#rep').append(select_rep).trigger('change');

            $('#customer_city').on('change', function () {
                if ($('#customer_city').val() != '') {
                    customer_list_table.column(7)
                        .search($('#customer_city option:selected').text())
                        .draw();
                } else {
                    customer_list_table.column(7)
                        .search('')
                        .draw();
                }
            });
            $('#rep').on('change', function () {
                if ($('#rep').val() != '') {
                    customer_list_table.column(8)
                        .search($('#rep option:selected').text())
                        .draw();
                } else {
                    customer_list_table.column(8)
                        .search('')
                        .draw();
                }
            });




            $('body').on('click', '#customer_history', function () {
                var data = customer_list_table.row($(this).parents('tr')).data();
                window.customer_history_model = $.confirm({
                    title: 'Customer History',
                    draggable: true,
                    boxWidth: '80%',
                    closeIcon: true,
                    useBootstrap: false,
                    buttons: {
                        close: function () {
                        }
                    },
                    content: 'url:' + BASE + 'customer_invoices/customer_history_modal/' + data['id'],
                    onContentReady: function () {

                    },
                    columnClass: 'medium',
                });
                return false;
            });


            $('#customerListTable tbody').on('click', 'button.customer_outstanding_button', function (e) {
                var $btn = $(this);
                $btn.button('loading');

                var data = customer_list_table.row($(this).parents('tr')).data();

                var params = {

                    customer_id: data['id']
                };

                $.ajax({
                    url: BASE + 'reports/generate_customer_outstanding_report',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {
                            window.open(response.report_url);
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


            $(document).on('click', '.delete_customer', function (e) {
                var data = customer_list_table.row($(this).parents('tr')).data();
                var parent = $(this).parents('tr');

                var delete_confirm = $.confirm({
                    title: "Delete Customer",
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
                                    url: BASE + 'customer/' + data['id'],
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

                                            customer_list_table
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
