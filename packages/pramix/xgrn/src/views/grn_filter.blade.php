{{formDropdown('Please Select GRN', 'grn',\Pramix\XGRN\Models\GRNModel::where('status', '!=' , 'D')->pluck('grn_code', 'id'), '', array('class'=> 'form-control select2', 'id' => 'grn_code_selected'))}}

