
<div class="card card-primary" id="product-details-card">
    <div class="card-body">
        <div class="row gutter">
            {{--<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">--}}
                {{--<div class="form-group no-margin">--}}
                    {{--<input data-toggle="tooltip" data-placement="top" title="Barcode" type="text" class="form-control" id="product_filter_barcode" name="product_filter_barcode"--}}
                           {{--placeholder="{{ __('xproduct::product.labels.barcode')}}">                      </div>--}}
            {{--</div>--}}
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    {{ Form::select('product_catagory', \Pramix\XProduct\Models\ProductCategoriesModel::pluck('category_name','id') , ''  , array('class' => 'form-control select2' , 'id' => 'item_category_code')) }}
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group no-margin" id="item_product_code_selector_div">
                    {{ Form::select('products', \Pramix\XProduct\Models\ProductsModel::orderBy('id', 'asc')->pluck('item_code','id') , ''  , array('class' => 'form-control select2' , 'id' => 'item_product_code','title' => '')) }}
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <input data-toggle="tooltip" data-placement="top" title="Description" type="text" class="form-control" id="description"  name="description"
                           placeholder="{{ __('xproduct::product.labels.description')}}">
                </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <input data-toggle="tooltip" data-placement="top" title="Quantity" type="number" class="form-control" id="quantity" name="quantity"
                           placeholder="{{ __('xproduct::product.labels.quantity')}}">
                </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <input data-toggle="tooltip" data-placement="top" title="Unit Price" type="number" class="form-control" id="unit_price" name="unit_price"
                           placeholder="{{ __('xproduct::product.labels.unit_price')}}">
                </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <input type="number" data-toggle="tooltip" data-placement="top" title="Product Discount" class="form-control" id="product_discount" name="discount"
                           placeholder="{{ __('xproduct::product.labels.discount')}}">


                </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    {{ Form::select('discount_type', getConfig('DISCOUNT_TYPE'), isset($customer->discount_type) ? $customer->discount_type : getConfigValue('DISCOUNT_TYPE') , array('class' => 'form-control', 'id' => 'product_discount_type')) }}
               </div>
            </div>
            <div class="col-lg-1 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <a href="javascript:void(0)" class="btn btn-primary"
                       id="update_item_row_btn">{{ __('xproduct::product.buttons.product_update')}}</a>

                    <a href="javascript:void(0)" class="btn btn-primary"
                       id="add_item_row_btn">{{ __('xproduct::product.buttons.product_add')}}</a>
                </div>
            </div>
            @can('ADD_PRODUCTS')
            <div class="col-lg-1 col-md-4 col-sm-6 col-xs-12">
                <div class="form-group no-margin">
                    <a href="#" class="btn btn-link"
                       id="create_new_product_model">{{__('xproduct::product.buttons.new_product')}}</a>
                </div>
            </div>
            @endcan

        </div>


    </div>
</div>

<div class="row gutter">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table id="ProductsTable" class="table table-bordered no-margin"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ __('xproduct::product.labels.category')}}</th>
                    <th>{{ __('xproduct::product.labels.item')}}</th>
                    <th>{{ __('xproduct::product.labels.description')}}</th>
                    <th>{{ __('xproduct::product.labels.quantity')}}</th>
                    <th>{{ __('xproduct::product.labels.unit_price')}}</th>
                    <th>{{ __('xproduct::product.labels.discount')}}</th>
                    <th>{{ __('xproduct::product.labels.product_discount_type')}}</th>
                    <th></th>
                    <th>{{ __('xproduct::product.labels.sub_total')}}</th>
                    <th>{{ __('xproduct::product.labels.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>