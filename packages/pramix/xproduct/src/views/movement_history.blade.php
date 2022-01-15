@extends('layouts.app')

@section('content')
<!-- Dashboard wrapper starts -->
<div class="dashboard-wrapper">

    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">

                    <h4>{{ __('xproduct::product.headings.product_movement_history')}}</h4>

                </div>
            </div>

        </div>
    </div>
    <!-- Top bar ends -->

    <!-- Main container starts -->
    <div class="main-container">

        <!-- Row starts -->
        <div class="row gutter">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="table-responsive">
                                <input type="hidden" name="product_id" id="product_id" value="{{ $product_id or '' }}">

                    <table id="moveHistoryTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                        <thead>
                            <tr>

                                <th>{{ __('inventory.labels.transaction_type')}}</th>
                                <th>{{ __('inventory.labels.date')}}</th>
                                <th>{{ __('inventory.labels.user')}}</th>
                                <th>{{ __('inventory.labels.order_number')}}</th>
                                <th>{{ __('inventory.labels.qty_before')}}</th>
                                <th>{{ __('inventory.labels.qty')}}</th>
                                <th>{{ __('inventory.labels.qty_after')}}</th>
                            </tr>
                        </thead>
                        <tfoot>
                              <tr>

                                <th>{{ __('inventory.labels.transaction_type')}}</th>
                                <th>{{ __('inventory.labels.date')}}</th>
                                <th>{{ __('inventory.labels.user')}}</th>
                                <th>{{ __('inventory.labels.order_number')}}</th>
                                <th>{{ __('inventory.labels.qty_before')}}</th>
                                <th>{{ __('inventory.labels.qty')}}</th>
                                <th>{{ __('inventory.labels.qty_after')}}</th>
                            </tr>
                        </tfoot>
                        <tbody>



                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- Row ends -->





    </div>
    <!-- Main container ends -->

</div>
<!-- Dashboard wrapper ends -->

<script>
    $(document).ready(function () {

        $('#moveHistoryTable').DataTable({
            processing: true,
            serverSide: true,
           // 'iDisplayLength': 10,
            ajax: BASE + 'product/get_product_movement_history/' + $('#product_id').val(),
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0 , 1 , 2 , 3 , 4 , 5 , 6]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0 , 1 , 2 , 3 , 4 , 5 , 6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                         columns: [0 , 1 , 2 , 3 , 4 , 5 , 6]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                         columns: [0 , 1 , 2 , 3 , 4 , 5 , 6]
                    }
                },
            ],
            columns: [
                {data: 'transaction_type', name: 'transaction_type'},
                {data: 'date', name: 'date'},
                {data: 'user', name: 'user'},
                {data: 'order_number', name: 'order_number'},
                {data: 'qty_before', name: 'qty_before'},

                {data: 'qty', name: 'qty'},
                {data: 'qty_after', name: 'qty_after'},

            ]
        });

    });
</script>
