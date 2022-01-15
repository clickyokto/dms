@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>Reports</h4>
                </div>
            </div>

        </div>
    </div>
    <!-- Top bar ends -->

    <!-- Main container starts -->
    <div class="main-container">
        <!-- Row starts -->
        <div class="row gutter">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <h5>Out Standing Reports</h5>
                <div class="list-group">
                <a href="{{url('reports/outstanding_report')}}" class="list-group-item ">Outstanding Report</a>

                <a href="{{url('reports/outstanding_report_period_wise')}}" class="list-group-item ">Outstanding Report Period Wise</a>

                    <a href="{{url('reports/payment_summary_report')}}" class="list-group-item">Payment Summary</a>

                <a href="{{url('reports/cheque_return_outstanding_report')}}" class="list-group-item ">Cheque Return Outstanding Report</a>


                {{--<h5><strong>Sales</strong></h5>--}}
                {{--<div class="list-group">--}}
                    {{--<a href="{{url('reports/sales_by_product_summary_report')}}" class="list-group-item">Sales by Product Summary</a>--}}
                    {{--<a href="{{url('reports/sales_order_summary_report')}}" class="list-group-item">Sales Order Summary</a>--}}
                    {{--<a href="{{url('reports/payment_summary_report')}}" class="list-group-item">Payment Summary</a>--}}
                {{--</div>--}}


            {{--</div>--}}

            {{--<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">--}}
                {{--<h5><strong>Inventory</strong></h5>--}}
                {{--<div class="list-group">--}}
                    {{--<a href="{{url('reports/products_report')}}" class="list-group-item">Products Report</a>--}}
                {{--</div>--}}


            {{--</div>--}}

            {{--<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">--}}
                {{--<h5><strong>Purchasing</strong></h5>--}}
                {{--<div class="list-group">--}}
                    {{--<a href="{{url('reports/purchasing_summary_report')}}" class="list-group-item">Purchase order Summary Report</a>--}}
                    {{--<a href="{{url('reports/billing_summary_report')}}" class="list-group-item">Billing Summary</a>--}}

                {{--</div>--}}

                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <h5>Sales Reports</h5>
                <div class="list-group">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <h5>Inventory Reports</h5>
                <div class="list-group">
                    <a href="{{url('reports/low_stock_products_report')}}" class="list-group-item">Low stock products report</a>
                    <a href="{{url('reports/inventory_movement_summary_report')}}" class="list-group-item ">Inventory Movement Summary Report</a>

                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <h5>Commission Reports</h5>
                <div class="list-group">
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

    });
</script>
@endsection
