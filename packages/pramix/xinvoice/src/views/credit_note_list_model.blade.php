
    <input type="hidden" name="page" id="page" value="{{ $page ?? '' }}">
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Credit Note List</h4>
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
                                <th>Credit note code</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Status</th>
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


    <script>
        $(document).ready(function () {

            var credit_note_list_table  = $('#orderListTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                'iDisplayLength': 15,
                ajax: BASE + 'get_used_credit_note_details_model/{{$invoice_id}}',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'invoice_return_code', name: 'invoice_return_code'},
                    {data: 'invoice_return_date', name: 'invoice_return_date'},
                    {data: 'customer', name: 'customer'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });
        });
    </script>

