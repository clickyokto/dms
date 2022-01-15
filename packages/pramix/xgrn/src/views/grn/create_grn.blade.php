@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">

                        @if(isset($grn->id))
                            <h4>{{__('xgrn::grn.headings.edit_grn')}}</h4>
                        @else
                            <h4>{{__('xgrn::grn.headings.new_grn')}}</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                                id="grn_save_btn">{{ __('xgrn::grn.buttons.save')}}</button>
                        <button class="btn btn-primary"
                                id="grn_save_and_new_btn">{{ __('xgrn::grn.buttons.save_and_new')}}</button>
                        <button class="btn btn-primary"
                                id="grn-update-btn">{{ __('xgrn::grn.buttons.update')}}</button>
                        <button class="btn btn-success"
                                id="generate_grn_pdf">{{ __('xgrn::grn.buttons.genarate_pdf')}}</button>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/po_return'}}" method="POST" id="grn_form">
                @csrf
                <input type="hidden" name="grn_id" id="grn_id"
                       value="{{ $grn->id ?? '' }}">
                <input type="hidden" name="page" id="ref_id" value="{{ $grn->id ?? '' }}">
                <input type="hidden" name="page" id="ref_type" value="GRN">
                <input type="hidden" name="record_product_update_id" id="record_product_update_id">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">

                <input type="hidden" name="grn_status" id="grn_status" value="{{ $grn->status ?? '' }}">

                <div class="row gutter">


                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                        <div class="card" id="order_details_panel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{formText(__('xgrn::grn.labels.grn_code'), 'grn_code', $grn->grn_code ?? '', array( 'class' => 'form-control' , 'id' => 'grn_code' , 'readonly' => 'readonly'))}}

                                    </div>
                                    <div class="col-sm-6">
                                        {{formDate(__('xgrn::grn.labels.grn_date'), 'order_date_created', old('date_created', isset($grn->order_date) ? $grn->order_date :Carbon\Carbon::today()->format('Y-m-d')), array( 'class' => 'form-control' , 'id' => 'order_date_created'))}}

                                    </div>
                                </div>
                                <span id="display_status">
                                    @if(isset($grn->status) && $grn->status=='D')
                                        <span class="label label-danger">Draft</span>
                                    @elseif(isset($grn->status) && $grn->status=='A')
                                        <span class="label label-success">Approved</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" id="all_details_panel">
                    <div id="overlay"></div>
                    <div class="card card-primary" id="grn-details-card">
                        <div class="card-body">
                            <div class="row gutter" id="grn-details-panel">
                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">


                                    {{--@php--}}
                                        {{--$categories = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->get();--}}
                                    {{--@endphp--}}
                                    {{--<div class="form-group no-margin">--}}

                                        {{--<select class="form-control select2" name="product_category" id="item_category_code">--}}
                                            {{--@foreach($categories as $category)--}}
                                                {{--<optgroup label="{{$category->category_name}}">--}}

                                                    {{--@php $childs = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', $category->id)->get(); @endphp--}}
                                                    {{--@if(count($childs) > 0)--}}
                                                        {{--<ul class="list-group">--}}
                                                            {{--@foreach($childs as $child)--}}
                                                                {{--<option value="{{$child->id ?? ''}}">{{$child->category_name ?? ''}}</option>--}}
                                                            {{--@endforeach--}}
                                                        {{--</ul>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}

                                    {{ Form::select('stock_id', \Pramix\XProduct\Models\ProductsModel::pluck('stock_id','id') , ''  , array('class' => 'form-control select2' , 'id' => 'item_stock_id','placeholder' => 'Select Stock ID')) }}

                                </div>

                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin" id="grn_item_product_code_selector_div">
                                        {{ Form::select('products', \Pramix\XProduct\Models\ProductsModel::pluck('item_code','id') , ''  , array('class' => 'form-control select2' , 'id' => 'item_product_code','title' => '')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin">
                                        <input type="text" data-toggle="tooltip" data-placement="top"
                                               title="{{ __('xproduct::product.labels.description')}}"
                                               class="form-control" id="description" name="description"
                                               placeholder="{{ __('xproduct::product.labels.description')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin">
                                        <input data-toggle="tooltip" data-placement="top"
                                               title="{{ __('xproduct::product.labels.unit_price')}}" type="number"
                                               class="form-control" id="unit_price" name="unit_price"
                                               placeholder="{{ __('xproduct::product.labels.unit_price')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin">
                                        <input data-toggle="tooltip" data-placement="top"
                                               title="Selling Price" type="number"
                                               class="form-control" id="selling_price" name="selling_price"
                                               placeholder="Selling Price">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin">
                                        <input data-toggle="tooltip" data-placement="top" title="Qty" type="number"
                                               class="form-control" id="delivered_qty"
                                               name="delivered_qty"
                                               placeholder="Qty">
                                    </div>
                                </div>


                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group no-margin">
                                        <a href="javascript:void(0)" class="btn btn-success"
                                           id="update_item_row_btn">{{ __('xproduct::product.buttons.product_update')}}</a>

                                        <a href="javascript:void(0)" class="btn btn-success"
                                           id="add_item_row_btn">{{ __('xproduct::product.buttons.product_add')}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row gutter">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="GRNProductsTable" class="table table-bordered no-margin"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
<th>#</th>
                                        <th>Stock ID</th>
                                        <th>{{ __('xproduct::product.labels.item')}}</th>
                                        <th>{{ __('xproduct::product.labels.description')}}</th>
                                        <th>{{ __('xproduct::product.labels.unit_price')}}</th>
                                        <th>Selling Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>{{ __('xproduct::product.labels.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row gutter">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

                        <div class="card">
                            <div class="card-body">
                                {{formTextArea(__('xgrn::grn.labels.notes_n_terms'), 'remarks', $grn->remarks ?? '', array( 'class' => 'form-control' , 'id' => 'remarks', 'rows' => 2))}}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        {{--<div class="card" id="price_panel">--}}
                            {{--<div class="card-body">--}}
                                {{--<div class="form-horizontal">--}}
                                    {{--<div class="form-group  gutter">--}}
                                        {{--<label class=" control-label"--}}
                                               {{--for="sub_total">{{ __('xinvoice::invoice.labels.subtotal')}}</label>--}}

                                            {{--<input type="number" class="form-control" id="sub_total"--}}
                                                   {{--name="sub_total"--}}
                                                   {{--value="{{ $grn->sub_total ?? '0.00' }}"--}}
                                                   {{--disabled="true">--}}

                                    {{--</div>--}}


                                    {{--<div class="form-group  gutter">--}}
                                        {{--<label class="control-label"--}}
                                               {{--for="total">{{ __('xinvoice::invoice.labels.total')}}</label>--}}

                                            {{--<input type="number" class="form-control" id="total" name="total"--}}
                                                   {{--value="{{ $grn->total ?? '0.00' }}" disabled="true">--}}

                                    {{--</div>--}}

                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    </div>

                </div>

            </form>
        </div>

    </div>
@endsection
@section('include_js')

    <script src="{{ asset('/pramix/js/grn_js.js') }}"></script>

@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $('#overlay').hide('slow');

            @if(isset($grn->id))
            $("#remarks").Editor("setText", "{{$grn->remarks ?? ''}}")
            $('#purchase_order_code_selected').val({{$grn->purchase_order_id}}).trigger('change');
            // changeSupplier.run($("#supplier_code_selected").val(), '', '');
            $('#overlay').hide('slow');
            @endif

            @if(isset($grn->status) && $grn->status != 'D')
            $('#grn-details-card').hide('slow');
            $('#price_panel :input').prop("disabled", true);
            $('#supplier_filter :input').prop("disabled", true);
            $('#order_details_panel :input').prop("disabled", true);
            $('#grn-approve-btn').hide('slow');
            @endif
        });
    </script>
@endsection
