
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{__('xgrn::grn.headings.grn_list')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="row gutter">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="orderListTable" class="table table-striped table-bordered no-margin" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xgrn::grn.labels.grn_code')}}</th>
                                <th>{{ __('xgrn::grn.labels.order_date')}}</th>
                                <th>{{ __('xgrn::grn.labels.supplier')}}</th>
                                <th>{{ __('xgrn::grn.labels.status')}}</th>
                                <th>{{ __('xgrn::grn.labels.actions')}}</th>
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


    <script>
        $(document).ready(function () {

            var grn_list_table  = $('#orderListTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: BASE + 'get_used_grn_details_model/{{$purchase_order_id}}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'grn_code', name: 'grn_code'},
                    {data: 'grn_date', name: 'grn_date'},
                    {data: 'supplier', name: 'supplier'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });
        });
    </script>

