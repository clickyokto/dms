@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />

@endsection
@section('content')
    @if($page=='bill')
        <?php
        if (!isset($bill->id))
            $header = "New Bill";
        else
            $header = 'Edit Bill';
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}} {{$bill->bill_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/bill'}}" method="POST" id="bill_form">
                @csrf
                <input type="hidden" name="bill_id" id="bill_id" value="{{ $bill->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $bill->id ?? '' }}">
                <input type="hidden" name="ref_type" id="ref_type" value="SB">
                <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $bill->supplier_id ?? '' }}">
                <input type="hidden" name="page" id="ref_type" value="PM">
                <input type="hidden" name="record_bill_update_id" id="record_bill_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="bill_status" id="bill_status" value="{{ $bill->status ?? '' }}">
                <input type="hidden" name="invoice_id" id="invoice_id" value="">


                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="supplier_detail_panel">
                            <div class="card-body">
                                @include('xsupplier::supplier_filter')

                            </div>
                        </div>
                        <div class="alert alert-info alert-dismissible" id="supplier_info_alert"></div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" >
                        @include('xpurchase_order::payment_filter')
                    </div>
                </div>
                <!-- Row ends -->
                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    @include('xpurchase_order::bill.bill_filter')
                    <div class="form-group">
                    </div>

                    <!-- Row starts -->
                    <div class="row gutter">

                    </div>
                </div>
            </form>


        </div>
        <!-- Main container ends -->

    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/bill_js.js') }}"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {


        });
    </script>
@endsection

