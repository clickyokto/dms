<div class="main-container">
    <!-- Row starts -->
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<h4>{{$customer->fname}} {{$customer->lname}}</h4>
            <div class="table-responsive">
                <table id="customer_history_table" class="table table-striped table-bordered no-margin"
                       cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('xinvoice::invoice.labels.invoice_no')}}</th>
                        <th>{{ __('xinvoice::invoice.labels.invoice_date')}}</th>
                        <th>{{ __('xinvoice::invoice.labels.total')}}</th>
                        <th>{{ __('xinvoice::invoice.labels.paid')}}</th>
                        <th>Credit</th>
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


        </div>
    </div>
    <!-- Row ends -->

</div>

<script>
    $(document).ready(function () {
        var customer_history_table = $('#customer_history_table').DataTable({
            order: [[0, "desc"]],
            processing: true,
            serverSide: true,
            iDisplayLength: 15,
            ajax: BASE + 'invoices_list/{{$customer_id}}/customer',
            bInfo: false,

            columns: [
                {data: 'id', name: 'id', 'bVisible': false},
                {data: 'invoice_code', name: 'invoice_code'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'total', name: 'total',className: 'dt-body-right' },

                {data: 'paid_amount', name: 'paid_amount' ,className: 'dt-body-right'},
                {data: 'credit', name: 'credit',className: 'dt-body-right' },
                {data: 'balance', name: 'balance' ,className: 'dt-body-right'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'status', name: 'status'},
                {data: 'created_by', name: 'created_by'},
                {data: 'action', name: 'action'},
            ]
        });

        $(document).on('click', '#edit_invoice', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            window.open(url, '_blank');
        });

    });
</script>
