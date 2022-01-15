@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))


@section('content')

    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>
                        <?php
                        if (!isset($grn_return->id))
                        echo (__('xgrn::grn.headings.new_grn_return'));
                        else
                          echo ( __('xgrn::grn.headings.edit_grn_return'));
                        ?>
                        </h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                           id="grn_return_save_btn">{{ __('xgrn::grn.buttons.save')}}</button>
                        @can('APPROVE_GRN_RETURN')
                        <button class="btn btn-primary"
                           id="grn_return_approve_btn">Approve</button>
                        @endcan
                        <button class="btn btn-primary"
                           id="grn_return_update_btn">{{ __('xgrn::grn.buttons.update')}}</button>
                        <button class="btn btn-default"
                           id="generate_grn_return_pdf">{{ __('xgrn::grn.buttons.genarate_pdf')}}</button>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/grn_return'}}" method="POST" id="grn_return_form">
                @csrf
                <input type="hidden" name="grn_return_id" id="grn_return_id" value="{{ $grn_return->id ?? '' }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $grn_return->id ?? '' }}">
                <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $grn_return->quotation_id ?? '' }}">
                <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $grn_return->supplier_id ?? '' }}">
                <input type="hidden" name="page" id="ref_type" value="GRNR">
                <input type="hidden" name="isajax" id="isajax" value="{{ Request::ajax() }}">
                <input type="hidden" name="record_product_update_id" id="record_product_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">

                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="supplier_filter">
                        <div class="card">

                            <div class="card-body">
                        @include('xgrn::grn_filter')
                        @include('xsupplier::supplier_filter')
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="grn_filter">

                        <div class="alert alert-info alert-dismissible" id="products_info_alert">

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card" id="grn_return_details_panel">
                            <div class="card-body">
                                  {{formDate(__('xgrn::grn.labels.order_date'), 'grn_return_date_created', old('date_created', isset($grn_return->grn_return_date) ? $grn_return->grn_return_date :Carbon\Carbon::today()->format('Y-m-d')), array( 'class' => 'form-control' , 'id' => 'grn_return_date_created'))}}
                                <span id="display_status">
                                    @if(isset($grn_return->status) && $grn_return->status=='D')
                                        <span class="label label-danger">Draft</span>
                                    @elseif(isset($grn_return->status) && $grn_return->status=='A')
                                        <span class="label label-success">Approved</span>
                                    @endif
                                </span>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    @include('xgrn::grn_returns.grn_return_product_filter')

                    <div class="row gutter">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                        {{formTextArea(__('xgrn::grn.labels.remarks'), 'remarks', $grn_return->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks', 'rows' => 2))}}

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="card" id="price_panel">
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="sub_total">{{ __('xgrn::grn.labels.subtotal')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="sub_total"
                                                       name="sub_total"
                                                       value="{{ $grn_return->sub_total ?? '0.00' }}"
                                                       disabled="true">
                                            </div>
                                        </div>
                                        @if(getConfig('GRN_TAX') == 'TRUE')
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_vat" name='checked_vat' value="vat" @if(isset($grn_return->vat_amount)&& $grn_return->vat_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_vat">VAT</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="vat_amount" name="vat_amount"
                                                           value="{{ $grn_return->vat_amount ?? '0' }}">
                                                    <div class="input-group-addon">15%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <div class="col-sm-3 right-text">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" id="checked_nbt" name = 'checked_nbt' value="nbt" @if(isset($grn_return->nbt_amount)&& $grn_return->nbt_amount!=0) checked @endif/>
                                                    <label
                                                        for="checked_nbt">NBT</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="nbt_amount" name="nbt_amount"
                                                           value="{{ $grn_return->nbt_amount ?? '0' }}">
                                                    <div class="input-group-addon">2%</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="discount">{{ __('xgrn::grn.labels.discount')}}</label>
                                            <div class="col-sm-9">
                                                <div class="row gutter">
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" id="grn_return_discount"
                                                               name="grn_return_discount"
                                                               value="{{ $grn_return->discount ?? '0' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($grn_return->discount_type) ? $grn_return->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'grn_return_discount_type')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row gutter">
                                            <label class="col-sm-3 control-label"
                                                   for="total">{{ __('xgrn::grn.labels.total')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="total" name="total"
                                                       value="{{ $grn_return->total ?? '0.00' }}" disabled="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="customer_comment_content">

                        @include('xgeneral::comment_section')
                    </div>
                </div>

                <!-- Row ends -->


            </form>


        </div>
        <!-- Main container ends -->

    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
    <script src="{{ asset('/pramix/js/grn_return_js.js') }}"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {

            $("#grn_code_selected").change(function (e) {

                window.onbeforeunload = function() {
                    return "Are you sure you want to leave?";
                };

                var params = {
                    grn_id: $(this).val()
                };

                e.preventDefault();
                $.ajax({
                    url: BASE + 'get_grn_details',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {

                        if (response.status == 'success') {

                            changeSupplier.run(response.grn.supplier_id, '', '', '');
                            if (  $("#ref_id").val()=='')
                            createPOreturnCode.run();
                            $("#grn_return_item_product_code , #grn_return_item_category_code").find('option').remove();

                            $.each(response.grn_products, function () {
                                $("#grn_return_item_product_code").append($("<option />").val(this.product.id).text(this.product.item_code));
                            });

                            $.each(response.categories, function () {
                                $("#grn_return_item_category_code").append($("<option />").val(this.id).text(this.category_name));
                            });

                            var selectedcategory = new Option('Please select category', '', true, true);
                            $('#grn_return_item_category_code').append(selectedcategory).trigger('change.select2');

                            var selectedproduct = new Option('Please select product', '', true, true);
                            $('#grn_return_item_product_code').append(selectedproduct).trigger('change.select2');

                        } else {
                            notification(response);
                        }
                    },
                    error: function (errors) {
                        notification(errors);
                    }

                });
                e.preventDefault();
                return false;
            });

            @if(isset($grn_return->grn_id))
            $('#grn_code_selected').val({{$grn_return->grn_id}}).trigger('change');
            $("#remarks").Editor("setText", "{{$grn_return->remarks ?? ''}}");
            $('#overlay').hide('slow');
            @endif

            @if(isset($grn_return->status) && $grn_return->status != 'D')
            $('#product-details-card').hide('slow');
            $('#price_panel :input').prop("disabled", true);
            $('#supplier_filter :input').prop("disabled", true);
            $('#grn_return_details_panel :input').prop("disabled", true);
            $('#grn_return_approve_btn').hide('slow');
            @endif

        });
    </script>
@endsection

