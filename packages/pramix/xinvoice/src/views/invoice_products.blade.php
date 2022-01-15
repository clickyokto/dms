<div class="row gutter">
    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
        <div class="card" id="">
            <div class="card-body">
                @include('xcustomer::customer_filter', ['create_new' => false])

            </div>


        </div>
    </div>
    <div class="col-sm-4">
        <div class="card" id="">
            <div class="card-body">
            {{--<div class="form-group no-margin">--}}
                {{--@php--}}
                    {{--$categories = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->get();--}}
                {{--@endphp--}}
                {{--<div class="form-group">--}}
                    {{--<label for="customer_name_selected">Product Category</label>--}}
                    {{--<select id="product_categories" data-toggle="tooltip" data-placement="top" title="" data-original-title="--}}
{{--Product Category" class="form-control select2" name="product_catagory" id="item_category_code">--}}
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

            {{--</div>--}}

                <div class="form-group ">
                    <label for="customer_name_selected">Stock ID</label>
                    {{ Form::select('stock_id', \Pramix\XProduct\Models\ProductsModel::pluck('stock_id','id') , ''  , array('class' => 'form-control select2' ,'placeholder' => 'Product Stock ID', 'id' => 'stock_id','title' => '')) }}

                </div>
            <div class="form-group ">
                <label for="customer_name_selected">Select Product</label>
                {{ Form::select('products', \Pramix\XProduct\Models\ProductsModel::pluck('item_code','id') , ''  , array('class' => 'form-control select2' , 'id' => 'item_product_code','title' => '')) }}

            </div>
                <div class="form-group ">
                    <button class="btn btn-success btn-block" id="search_invoice_products">Search</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">



    </div>
</div>


<div class="table-responsive">
    <table id="invoiceListTable" class="table table-striped table-bordered no-margin"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th></th>
            <th>{{ __('xinvoice::invoice.labels.invoice_no')}}</th>
            <th>{{ __('xinvoice::invoice.labels.invoice_date')}}</th>
            <th>{{ __('xinvoice::invoice.labels.total')}}</th>
            <th>{{ __('xinvoice::invoice.labels.paid')}}</th>
            <th>{{ __('xinvoice::invoice.labels.balance')}}</th>
            <th>Payment Status</th>
            <th>{{ __('xinvoice::invoice.labels.status')}}</th>
            <th>{{ __('xinvoice::invoice.labels.created_by')}}</th>
            <th>Action</th>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function () {

    $('#stock_id').select2();
        $('#item_product_code').select2();


    var invoice_list_table  =  $('#invoiceListTable').DataTable({
            'iDisplayLength': 15,
           data:[],
            order: [[0, "desc"]],

            columns: [
                {data: 'id', name: 'id', 'bVisible': false},
                {data: 'invoice_code', name: 'invoice_code'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'total', name: 'total', className: 'dt-body-right'},
                {data: 'paid_amount', name: 'paid_amount', className: 'dt-body-right'},
                {data: 'balance', name: 'balance', className: 'dt-body-right'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'status', name: 'status'},
                {data: 'created_by', name: 'created_by'},
                {data: 'action', name: 'action'},
            ]
        });

        $("#search_invoice_products").click(function (e) {

            var params = {
                customer_id: $('#customer_id_selected').val(),
                product_id: $('#item_product_code').val(),

            };

            $.ajax({
                url: BASE + 'invoice/search_invoice_prouducts',
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'error') {
                        notification(response);


                    } else {
                        var column_data = response.data;
                        invoice_list_table.rows.add(column_data).draw(false);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });

        });


        $("#search_mobile").intlTelInput({
            preferredCountries: ['LK'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc

        });

        $("#search_telephone").intlTelInput({
            preferredCountries: ['LK'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc
        });

        var selected_product_category = new Option('Select Product Category', '', true, true);
        $('#item_category_code').append(selected_product_category).trigger('change');
        var selected_product = new Option('Select Product', '', true, true);
        $('#item_product_code').append(selected_product).trigger('change');


        var selectedcustomerCode = new Option('Select Customer Code', '', true, true);
        var selectedcustomerName = new Option('Select Customer name', '', true, true);


            $('#customer_name_selected').append(selectedcustomerCode).trigger('change');
            $('#customer_id_selected').append(selectedcustomerName).trigger('change');



        var customer_name_selected = $('#customer_name_selected').select2({
            ajax: {
                url: BASE + 'get_select_two_customer_code_filter',
                dataType: 'json',
                delay: 500,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.business_name,
                                id: item.id
                            }
                        })
                    };
                },
            }
        });

        var customer_id_selected = $('#customer_id_selected').select2({
            ajax: {
                url: BASE + 'get_select_two_customer_name_filter',
                dataType: 'json',
                delay: 500,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.company_name,
                                id: item.id
                            }
                        })
                    };
                },
            }
        });


        var changeCustomer = {
            run: function (id, phone, tel, filterBy) {


                var mobile_iso = $("#search_mobile").intlTelInput("getSelectedCountryData");
                var telephone_iso = $("#search_telephone").intlTelInput("getSelectedCountryData");

                var params = {
                    id: id,
                    phone: phone,
                    tel: tel,
                    mobile_country: mobile_iso['iso2'],
                    telephone_country: telephone_iso['iso2'],
                };
                $.ajax({
                    url: BASE + 'customer/get_customer_details',
                    type: 'POST',
                    dataType: 'JSON',
                    async: false,
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {


                            $("#customer_id").val(response.customer.id);


                            $("#search_telephone").val(response.customer.telephone);
                            $("#search_mobile").val(response.customer.mobile);

                            var customer_id_option = new Option(response.customer.fname + ' ' + response.customer.lname, response.customer.id, true, true);
                            $("#customer_id_selected").append(customer_id_option).trigger('change.select2');

                            var customer_name_option = new Option(response.customer.business_name, response.customer.id, true, true);
                            $("#customer_name_selected").append(customer_name_option).trigger('change.select2');


                        } else {
                            reset(filterBy);
                            notification(response);
                        }

                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });
                return false;

            }
        }


        $("#stock_id").change(function (e) {
$('#item_product_code').val($("#stock_id").val()).trigger('change.select2');
        });
        $("#item_product_code").change(function (e) {
            $('#stock_id').val($("#item_product_code").val()).trigger('change.select2');
        });

        $("#customer_name_selected , #customer_id_selected ").change(function (e) {
            if (this.value != '') {
                changeCustomer.run(this.value, '', '');
            } else
                reset('');

            // if ($("#invoice_code_selected").length) {
            //     getInvoices.run();
            // }

        });

    });
</script>