@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />

@endsection
@section('content')
    @if($page=='cheque')
        <?php
        if (!isset($cheque->id))
            $header = 'New Cheque';
        else
            $header = 'Edit Cheque';
        ?>
    @endif
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{$header}} {{$cheque->cheque_code ?? ''}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                                id="cheque_save_btn">{{ __('xinvoice::invoice.buttons.save')}}</button>
                        <button class="btn btn-primary"
                                id="cheque_update_btn">{{ __('xinvoice::invoice.buttons.update')}}</button>
                        <button class="btn btn-default"
                                id="cheque_print">Print</button>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/cheque'}}" method="POST" id="cheque_form">
                @csrf
                <input type="hidden" name="cheque_id" id="cheque_id" value="{{$cheque->id ?? ''}}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $cheque->id ?? ''}}">
                <input type="hidden" name="ref_type" id="ref_type" value="CHQ">
                <input type="hidden" name="page" id="ref_type" value="PM">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
                <input type="hidden" name="cheque_status" id="cheque_status" value="{{ $cheque->status ?? '' }}">

                <div class="card" id="cheque-details-card">
                    <div class="card-header">
                        <h4>Cheque Details</h4>
                    </div>

                    <div class="card-body">
                        <div class="row gutter">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{formDate('Create Date', 'date', $cheque->date ?? \Carbon\Carbon::today(), array( 'class' => 'form-control' , 'id' => 'date'))}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{formDropdown('Bank', 'bank',getConfig('BANKS'),isset($cheque->bank) ? $cheque->bank : getConfigValue('BANKS'), array('class' => 'form-control', 'id' => 'bank'))}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{ formText('Cheque #', 'cheque_no', $cheque->cheque_no ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'cheque_no'))}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{formDropdown('Payer Name', 'payer',[], '', array('data-loading_value' => $cheque->payer ?? '' ,'class' => 'common_auto_load_data form-control select2 validate[required]', 'data-element_type' => 'payer', 'id' => 'payer','placeholder'=> 'Payer'))}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <label
                                        for="cash_cheque">Cash Cheque</label>
                                    <br/>
                                    <input type="checkbox" name="cash_cheque" id="cash_cheque" @if(isset($cheque) && $cheque->cash_cheque=='1') checked
                                           @endif value="1"/>
                                    <label
                                        for="cash_cheque">Yes</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <label
                                        for="crossed">Crossed</label>
                                    <br/>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="yes" name="crossed"
                                               @if(isset($cheque) && $cheque->crossed=='1') checked
                                               @endif value="1"/>
                                        <label for="yes">Yes</label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="no" name="crossed"
                                               @if(isset($cheque) && $cheque->crossed=='0') checked
                                               @endif value="0"/>
                                        <label for="no">No</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row gutter">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{formDate('cheque_date', 'cheque_date', $cheque->cheque_date ?? '', array( 'class' => 'form-control' , 'id' => 'cheque_date'))}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                {{formText('Amount', 'amount', $cheque->amount ?? '', array( 'class' => 'form-control' , 'id' => 'amount'))}}
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                {{ formText('Remarks', 'remarks', $cheque->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks'))}}
                            </div>
                        </div>
                    </div>
                </div>


            </form>
        </div>
    </div>

@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/cheque_js.js') }}"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

        });
    </script>
@endsection

