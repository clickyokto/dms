@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')
    <link href="{{asset('/plugins/fineuploader/fine-uploader-gallery.min.css')}}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" rel="stylesheet"/>

@endsection
@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        @if(isset($product->id))
                            <h4>{{ __('xproduct::product.headings.update_product')}}</h4>
                        @else
                            <h4>{{ __('xproduct::product.headings.new_product')}}</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                           id="product-save-btn">{{ __('xproduct::product.buttons.save')}}</button>
                        <button class="btn btn-primary"
                           id="product-save-and-new">{{ __('xproduct::product.buttons.save_and_new')}}</button>
                        <button class="btn btn-primary"
                           id="product-update-btn">{{ __('xproduct::product.buttons.update')}}</button>

                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <form action="{{url('/').'/product'}}" method="POST" id="create_product_form">
                @csrf
                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id ?? '' }}">
                <input type="hidden" name="page" id="page" value="{{ $page ?? '' ?? '' }}">
                <input type="hidden" name="isajax" id="isajax" value="{{ Request::ajax() }}">
                <input type="hidden" name="ref_id" id="ref_id" value="{{ $product->id ?? '' }}">
                <input type="hidden" name="ref_type" id="ref_type" value="P">
                <input type="hidden" name="product_type" id="product_id" value="{{ $product->type ?? '' }}">
                <input type="hidden" name="product_discount" id="discount_id"
                       value="{{ $product_discount->id ?? '' }}">
            <div class="row gutter">


                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card" id="product-basic-details-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.panel_basic_details')}}</h4>
                            </div>

                            <div class="card-body">
                                {{ formText('Stock ID', 'stock_id', $product->stock_id ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'stock_id'))}}

                                {{ formText(__('xproduct::product.labels.product_name_code'), 'product_code', $product->item_code ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'product_code'))}}
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{formDropdown(__('xproduct::product.labels.product_type'), 'product_type',Config::get('system.product_types'),isset($product->type) ? $product->type : Config::get('system.default_product_type'), array('class' => 'form-control', 'id' => 'product_type'))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                        @php
                                        $categories = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->get();
                                        @endphp
                                        <div class="form-group">
                                            <label for="product_category">Category</label>
                                        <select class="form-control select2" name="product_category" id="product_category">
                                        @foreach($categories as $category)
                                                <optgroup label="{{$category->category_name}}">

                                            @php $childs = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', $category->id)->get(); @endphp
                                            @if(count($childs) > 0)
                                                <ul class="list-group">
                                                    @foreach($childs as $child)
                                                        <option value="{{$child->id ?? ''}}">{{$child->category_name ?? ''}}</option>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                        </select>
                                        </div>


                                    </div>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="manufacture">Manufacture</label>
                                {{ Form::select('manufacture', \Pramix\XProduct\Models\ManufactureModel::pluck('manufacture_name','manufacture_name'), isset($manufacture) ? $manufacture :'', ['class' => 'common_auto_load_data form-control select2','name' => 'manufacture' , 'data-loading_value' => $manufacture ?? '','placeholder'=> 'Select Manufacture' , 'id' => 'manufacture']) }}
                                        </div>
                                    </div>
                                </div>

                                {{formTextArea(__('xproduct::product.labels.description'), 'description', $product->description ?? '', array( 'class' => 'form-control' , 'id' => 'description', 'rows' => 3))}}
                            </div>
                        </div>

                        <div class="card" id="product-pic-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.picture')}}</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="media_type" id="media_type" value="{{getConfigArrayValueByKey('MEDIA_TYPES','products_media')}}">

                                @include('xmedia::add_multiple_images_template')

                            </div>
                        </div>
                        <div class="card" id="product-discount-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.discounts')}}</h4>
                            </div>
                            <div class="card-body">

                                @if(isset($product_discount) && $product_discount!=null)
                                    <div class="row gutter">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <strong>{{ __('xproduct::product.labels.discount')}}</strong>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            @if($product_discount->discount_type == 'percentage')
                                                {{$product_discount->amount}} %
                                            @else
                                                {{Helper::formatPrice($product_discount->amount)}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row gutter">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <strong>{{ __('xproduct::product.labels.discount_starts')}}</strong>
                                        </div>
                                        <div
                                                class="col-lg-8 col-md-8 col-sm-8 col-xs-12">{{$product_discount->start_date}}</div>
                                    </div>
                                    <div class="row gutter">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <strong>{{ __('xproduct::product.labels.discount_ends')}}</strong>
                                        </div>
                                        <div
                                                class="col-lg-8 col-md-8 col-sm-8 col-xs-12">{{$product_discount->end_date}}</div>
                                    </div>
                                    <div class="row gutter">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <strong>{{ __('xproduct::product.labels.discount_limit')}}</strong>
                                        </div>
                                        <div
                                                class="col-lg-8 col-md-8 col-sm-8 col-xs-12">{{$product_discount->limit}}</div>
                                    </div>
                                    <button class="btn btn-success"
                                            id="btn_discount_edit">{{ __('xproduct::product.buttons.discount_edit')}}</button>
                                @endif
                                <div id="product_discount_form">
                                    <div class="row gutter">
                                        <div class="col-lg-7 col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                        for="discount_amount">{{ __('xproduct::product.labels.discount')}}</label>
                                                <div class="row gutter">
                                                    <div class="col-md-5">
                                                        <input type="number" class="form-control"
                                                               name="discount_amount"
                                                               value="{{ $product->discount->amount ?? '' }}">
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($customer->discount_type) ? $customer->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'discount_type')) }}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gutter">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                        for="discount_start_time">{{ __('xproduct::product.labels.discount_starts')}}</label>
                                                <div class="row gutter">
                                                    <div class="col-md-5">
                                                        <input type="time"
                                                               value="{{ isset($product->discount->start_date) ? date("H:i", strtotime($product->discount->start_date)) :   Carbon\Carbon::now()->format('H:i') }}"
                                                               class="form-control" name="discount_start_time">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input
                                                                value="{{ isset($product->discount->start_date) ? date("Y-m-d", strtotime($product->discount->start_date)) :  Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                type="date" class="form-control"
                                                                name="discount_start_date">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                        for="discount_end_time">{{ __('xproduct::product.labels.discount_ends')}}</label>
                                                <div class="row gutter">
                                                    <div class="col-md-5">
                                                        <input type="time"
                                                               value="{{ isset($product->discount->end_date) ? date("H:i", strtotime($product->discount->end_date)) :  Carbon\Carbon::now()->format('H:i')}}"
                                                               class="form-control" name="discount_end_time">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input
                                                                value="{{ isset($product->discountend_date) ? date("Y-m-d", strtotime($product->discount->end_date)) :  Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                type="date" class="form-control"
                                                                name="discount_end_date">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row gutter">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                        for="discount_limit">{{ __('xproduct::product.labels.discount_limit')}}</label>
                                                <input type="number" class="form-control" name="discount_limit"
                                                       value="{{ $product->discount->limit ?? '' }}">
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">


                        <div class="card" id="inventory-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.panel_inventory')}}</h4>
                            </div>
                            <div class="card-body">
<div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label
                                                for="qty">{{ __('xproduct::product.labels.quantity_on_hand')}}</label>
@if(isset($product))
                                   @php $qty_on_hand = \Pramix\XInventory\Models\Inventory::getProductStock($product->id) @endphp
                                   @endif
                                        <input type="number" class="form-control" name="qty_on_hand" id="qty_on_hand"
                                               value="{{ $qty_on_hand ?? '0' }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="barcode">{{ __('xproduct::product.labels.barcode')}}</label>
                                        <input type="text" class="form-control" name="barcode"
                                               value="{{ $product->barcode ?? '' }}">
                                    </div>
                                </div>
</div>
                                <div class="row">

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label
                                                for="reorder_point">{{ __('xproduct::product.labels.reorder_point')}}</label>
                                        <input type="number" class="form-control" name="reorder_point"
                                               value="{{ $product->reorder_point ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label
                                                for="reorder_qry">{{ __('xproduct::product.labels.reorder_quantity')}}</label>
                                        <input type="number" class="form-control" name="reorder_qry"
                                               value="{{ $product->reorder_qty ?? 1 }}">
                                    </div>

                                </div>
                                </div>
                            </div>

                        </div>

                        <div class="card" id="cost-and-price-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.panel_cost_and_price')}}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="cost">{{ __('xproduct::product.labels.cost')}}</label>
                                            <input type="number" class="form-control" name="cost"
                                                   value="{{ $product->cost ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                    for="normal_price">{{ __('xproduct::product.labels.normal_price')}}</label>
                                            <input type="number" class="form-control" name="normal_price"
                                                   value="{{ $product->price ?? '' }}">
                                        </div>
                                    </div>

                                </div>


                            </div>

                        </div>


                        <div class="card" id="measurement-panel">
                            <div class="card-header">
                                <h4>{{ __('xproduct::product.headings.panel_measurements')}}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="length">{{ __('xproduct::product.labels.length')}}</label>
                                            <input type="text" class="form-control" name="length"
                                                   value="{{ $product->length ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="width">{{ __('xproduct::product.labels.width')}}</label>
                                            <input type="text" class="form-control" name="width"
                                                   value="{{ $product->width ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="height">{{ __('xproduct::product.labels.height')}}</label>
                                            <input type="text" class="form-control" name="height"
                                                   value="{{ $product->height ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="weight">{{ __('xproduct::product.labels.weight')}}</label>
                                            <input type="text" class="form-control" name="weight"
                                                   value="{{ $product->weight ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Row ends -->


            </div>
            </form>

        </div>
        <!-- Main container ends -->
    </div>
    <!-- Dashboard wrapper ends -->
@endsection
@section('include_js')

    <script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>

    <script src="{{ asset('/pramix/js/product_js.js') }}"></script>
    <script src="{{ asset('/pramix/js/media_js.js') }}"></script>
    <script src="{{asset('/plugins/fineuploader/fine-uploader.min.js')}}"></script>


@endsection

@section('custom_script')


    @include('xmedia::qq_template')

    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $("#product_location").select2({
                tags: true
            });

            var product_category = new Option('Product Category', '', true, true);
            $('#product_category').append(product_category);

            $('#product_category').val('{{$product->category_id ?? ""}}').trigger("change");


                    @if(!isset($product->store_location))
                var product_location = new Option('Please select location', '', true, true);
                $('#product_location').append(product_location).trigger('change');
           @endif




               window.initializeSelectTwo = function () {

               $(".common_auto_load_data").each(function () {
                   var $this = $(this);

                   if ($this.attr("data-loading_value") != '' && typeof $this.attr("data-loading_value") !== "undefined") {
                       var $option = $('<option selected>' + $this.attr("data-loading_value") + '</option>').val($this.attr("data-loading_value"));

                       $this.append($option).trigger('change'); // append the option and update Select2
                   }

                   $this.select2({


                       tags: true,
                       ajax: {
                           url: BASE + 'get_manufactures_list',
                           dataType: 'json',
                           delay: 500,
                           processResults: function (data) {
                               return {
                                   results: $.map(data, function (item) {
                                       return {
                                           text: item.name,
                                           id: item.name
                                       }
                                   })
                               };
                           },
                       },
                   });
               });
           };

            initializeSelectTwo();

        });
    </script>

@endsection
