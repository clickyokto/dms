<div class="card" id="payment_details_card_form">
    <div class="card-header" >
        <h4 id="payment_hedding">{{ __('xinvoice::invoice.labels.payment_details')}}</h4>
    </div>
    <div class="card-body">
        <div class="row gutter">

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    {{ Form::date('payment_date',
                        old('payment_date_created',
                            Carbon\Carbon::today()->format('Y-m-d')),
                        ['class'=>'form-control date-picker', 'id' => 'payment_date']) }}

                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <select class="form-control" id="payment_method"
                            name="payment_method">
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        <option value="credit">Over payments</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" id="cheque_date_col">
                <div class="form-group">
                    <input type="date" class="form-control"
                           id="cheque_date" name="cheque_date"
                           placeholder="Cheque Date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}">
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" id="cheque_bank_col">
            {{formDropdown('', 'cheque_bank',getConfig('BANKS_LIST'),'', array('class' => 'form-control', 'id' => 'cheque_bank'))}}
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="payment_ref_no"
                           name="payment_ref_no"
                           placeholder="{{ __('xinvoice::invoice.labels.ref_no')}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="payment_remarks"
                           name="payment_remarks"
                           placeholder="{{ __('xinvoice::invoice.labels.remarks')}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <input type="number" class="form-control"
                           id="payment_amount" name="payment_amount"
                           placeholder="{{ __('xinvoice::invoice.labels.amount')}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn btn-primary"
                       id="payment_add_item_btn">{{ __('xinvoice::invoice.buttons.product_add')}}</a>

                </div>
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn btn-primary"
                       id="payment_update_item_btn">{{ __('xinvoice::invoice.buttons.product_update')}}</a>

                </div>
            </div>
        </div>

        <div class="row gutter">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="paymentsTable"
                           class="table table-striped table-bordered "
                           cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Payment Code</th>
                            <th>Invoice Code</th>
                            <th>{{ __('xinvoice::invoice.labels.payment_date')}}</th>
                            <th>{{ __('xinvoice::invoice.labels.payment_method')}}</th>
                            <th>Cheque Date</th>
                            <th>Cheque Bank</th>
                            <th>{{ __('xinvoice::invoice.labels.ref_no')}}</th>
                            <th>{{ __('xinvoice::invoice.labels.remarks')}}</th>
                            <th>{{ __('xinvoice::invoice.labels.amount')}}</th>
                            <th>Cheque Status</th>
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



