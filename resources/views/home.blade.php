@extends('layouts.app')
@section('include_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" rel="stylesheet" media="screen">
@endsection
@section('content')
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Dashboard</h4>
                    </div>
                </div>

            </div>
        </div>
        <!-- Top bar ends -->

        <!-- Main container starts -->
        <div class="main-container">
            <!-- Row starts -->
            <div class="row gutter">

                @can('MANAGE_INVOICE')
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="pull-left"><strong><a href="{{url('invoice/create')}}"><i
                                                class="fa fa-plus"></i> Add Invoice</a></strong></div>
                                <div class="pull-right"><i class="icon-arrow-up-right2"></i> <a
                                        href="{{url('invoice/?invoice_type=invoice')}}"><i class="pe-7s-home"></i> <strong>View
                                            List</strong></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pull-left">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="pull-right number">Invoices {{$invoice_count ??'0'}}</div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('MANAGE_INVOICE')
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="pull-left"><strong><a href="{{url('invoice_return/create')}}"><i
                                                class="fa fa-plus"></i> Add Credit Not</a></strong></div>
                                <div class="pull-right"><i class="icon-arrow-up-right2"></i> <a
                                        href="{{url('invoice_return')}}"><i class="pe-7s-home"></i> <strong>View
                                            List</strong></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pull-left">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="pull-right number">CREDIT NOTE {{$invoice_return_count ??'0'}}</div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('MANAGE_PAYMENT')
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="pull-left"><strong><a href="{{url('payment/create')}}"><i
                                                class="fa fa-plus"></i> Add Payment</a> </strong></div>
                                <div class="pull-right"><i class="icon-arrow-up-right2"></i> <a
                                        href="{{url('payment')}}"><i class="pe-7s-home"></i> <strong>View
                                            List</strong></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pull-left">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="pull-right number">Payment {{$payment_count ??'0'}}</div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('MANAGE_GRN')
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="pull-left"><strong><a href="{{url('grn/create')}}"><i
                                                class="fa fa-plus"></i> Add GRN</a></strong></div>
                                <div class="pull-right"><i class="icon-arrow-up-right2"></i> <a
                                        href="{{url('grn')}}"><i class="pe-7s-home"></i> <strong>View
                                            List</strong></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pull-left">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="pull-right number">GRN {{$grn_count ??'0'}}</div>
                            </div>
                        </div>
                    </div>
                @endcan

            </div>
            <!-- Row ends -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="card height2">
                        <div class="card-header">
                            <h4>Low Stock Products <span class="text-right"></span></h4>

                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($low_stock_products as $low_stock_product)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-1"><span class="text-danger"><i
                                                        class="fa fa-circle"></i> </span></div>
                                            <div class="col-8">{{$low_stock_product->item_code ?? ''}}</div>
                                            <div class="col-3">{{$low_stock_product->qty_on_hand ?? ''}}</div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                    </div>

                </div>


            </div>


        </div>
        <!-- Main container ends -->

    </div>

@endsection
@section('include_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

@endsection



@section('custom_script')
    <script>
        $(document).ready(function () {


        });
    </script>

@endsection
