@extends('layouts.app')

@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Purchase Order Summary Report</h4>
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
                                    <label>Invoice Date</label>
                                    @include('xreports::common.report_date_range_select')
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        <select name="payment_status" class="select2" id="payment_status">
                                            <option value="All" selected>All Payment Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Partial">Partial</option>
                                            <option value="Complete">Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <br>
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


            $("#generate_report_btn").click(function (e) {

                $('#generate_report_btn').prop('disabled', true);
                var params = {
                    filter_details: $('#report-filter :input').serialize(),
                };

                e.preventDefault();
                $.ajax({
                    url: BASE + 'reports/generate_purchase_order_summary_report',
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
