{{formDropdown(__('xinvoice::invoice.labels.invoice_code'), 'Invoice',Pramix\XInvoice\Models\InvoiceModel::where('status', '!=' , 'D')->pluck('invoice_code', 'id'), '', array('class'=> 'form-control select2', 'id' => 'invoice_code_selected'))}}

