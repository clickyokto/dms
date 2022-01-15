
                        <div class="form-group">
                            <label class="sr-only"
                                   for="start_date">{{__('reports.labels.date_range')}}</label>
                            <select name="date_range" id="date_range" class="form-control  select2">
                                <option value="today">Today</option>
                                <option value="this_month">This Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="last_7_days">Last 7 Days</option>
                                <option value="last_30_days" selected>Last 30 Days</option>
                                <option value="custom_date_range">Custom Date Range</option>
                            </select>
                        </div>
                        <div id="custom_date_range">
                            <div class="form-group">
                                <label for="date_from"
                                       class="col-sm-4 control-label">From</label>
                                <div class="col-sm-8">
                                    {{ Form::date('date_from',
            old('date_created',
            Carbon\Carbon::now()->subMonth()->format('Y-m-d')    ),
            ['class'=>'form-control input-sm date-picker']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_to"
                                       class="col-sm-4 control-label">To</label>
                                <div class="col-sm-8">
                                    {{ Form::date('date_to',
            old('date_created',
            Carbon\Carbon::today()->format('Y-m-d')    ),
            ['class'=>'form-control input-sm date-picker']) }}
                                </div>
                            </div>
                        </div>

