<div class="card" id="payment-details-card-form">
    <div class="card-header" >
        <h4 id="payment_hedding">Mail Details</h4>
    </div>
    <div class="card-body" id="payment_panel">
        <div class="row gutter">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="name" > Customer Name </label>

                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <input type="email" class="form-control" id="name"
                           name="name" value="{{ $customer_name ?? '' }}"
                           placeholder="Customer Name">
                </div>
            </div>
        </div>
        <div class="row gutter">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="email" > Customer Email </label>

                    </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <input type="email" class="form-control" id="email"
                           name="email" value="{{ $customer_mail ?? '' }}"
                           placeholder="Customer Mail">
                </div>
            </div>
        </div>
        <div class="row gutter">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="mail_Body" > Message </label>

                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    {{formTextArea('', 'message', '', array( 'class' => 'form-control' , 'id' => 'message', 'rows' => 4))}}
                </div>
            </div>
        </div>
        <div class="row gutter">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <div class="checkbox checkbox-inline">
                        <input type="checkbox" id="add_invoice_pdf" value="add_invoice_pdf"/>
                        <label
                            for="add_invoice_pdf">Add Invoice PDF</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



