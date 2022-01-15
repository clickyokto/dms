@if(isset($hide_header) && $hide_header == TRUE)
    <div class="card-header">
        <h4>{{ __('xgeneral::general.headings.comment')}}</h4>
    </div>
    @endif
    <div class="card-body">

        <div class="form-group ">


            <textarea id="txtEditor" name="email_message" class="txtEditor" style="height: 300px"></textarea>
            <button id="comment_save_btn" class="btn btn-primary btn-block">Submit</button>
        </div>

        <div class="row gutter">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="table-responsive">
                    <table id="CommentsListTable"
                           class="table table-striped table-bordered" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('xgeneral::general.labels.comment_date')}}</th>
                            <th>{{ __('xgeneral::general.labels.comment')}}</th>
                            <th>{{ __('xgeneral::general.labels.comment_user')}}</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
