@extends('layouts.app')

@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Customer Outstanding Report</h4>
                    </div>
                </div>

            </div>
        </div>
        <!-- Top bar ends -->

        <!-- Main container starts -->
        <div class="main-container">
            <!-- Row starts -->
            <div class="row gutter">
                <div class="card">
                    <div class="card-body">
                        <form class="" action="#" id="report-filter">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        {{ Form::select('customer_id', \Pramix\XCustomer\Models\CustomerModel::all()->pluck('company_name','id') , ''  , array('class' => 'form-control select2' , 'id' => 'customer_id')) }}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                {{formDropdown('', 'customer_city',\Pramix\XGeneral\Models\CityModel::pluck('name_en','id'), '', array('class' => 'form-control select2', 'placeholder' => 'Select City','id' => 'customer_city'))}}
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        {{ Form::select('rep', \Pramix\XUser\Models\User::role('REPRESENTATIVE')->pluck('username', 'id') , ''  , array('class' => 'form-control select2' , 'id' => 'rep')) }}

                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <button id="generate_report_btn"
                                            class="btn btn-primary">Generate
                                    </button>
                                </div>
                            </div>
                        </form>
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

    <script src="{{ asset('/pramix/js/reports_js.js')}}"></script>


@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            var selectedcustomerCode = new Option('All Customers', '', true, true);
            $('#customer_id').append(selectedcustomerCode).trigger('change');

            var selecte_rep = new Option('All Rep', '', true, true);
            $('#rep').append(selecte_rep).trigger('change');

            $("#generate_report_btn").click(function (e) {

                $('#generate_report_btn').prop('disabled', true);
                var params = {
                    filter_details: $('#report-filter :input').serialize(),
                };

                e.preventDefault();
                $.ajax({
                    url: BASE + 'reports/generate_customer_outstanding_report',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        $('#generate_report_btn').prop('disabled', false);
                        var url = response.report_url
                        window.open(url, '_blank');


                    },

                });
                e.preventDefault();
                return false;
            });


        });
    </script>
@endsection
