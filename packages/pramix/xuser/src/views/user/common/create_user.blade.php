<div class="card-body">
    <form id="user_form" role="form" method="POST" action=""
          class="form-horizontal user-form">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id ?? '' }}">
        <input type="hidden" name="user_code" id="user_code" value="{{ $user_code ?? '' }}">

        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="user_name">{{__('xuser::user.labels.user_name')}}</label>
            <div class="col-xs-8">
                <input id="user_name" type="text"
                       class="form-control validate[required]"
                       name="user_name" value="{{ $user->username ?? '' }}" required
                       autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="name">{{__('xuser::user.labels.fname')}}</label>
            <div class="col-xs-8">
                <input id="fname" type="text" class="form-control validate[required]" name="fname"
                       value="{{ $user->fname ?? '' }}" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="name">{{__('xuser::user.labels.lname')}}</label>
            <div class="col-xs-8">
                <input id="lname" type="text" class="form-control" name="lname"
                       value="{{ $user->lname ?? '' }}" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="email">{{__('xuser::user.labels.email')}}</label>
            <div class="col-xs-8">
                <input id="email" type="email" class="form-control validate[required]" name="email"
                       value="{{ $user->email ?? '' }}" required>
            </div>
        </div>



        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="email">{{__('xuser::user.labels.role')}}</label>
            <div class="col-xs-8">
                {{ Form::select('role', Pramix\XUser\Models\Role::pluck('display_name', 'name'),isset($user_role) ? $user_role : '',array('class'=> 'form-control validate[required]', 'id' => 'role')) }}
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="password">{{__('xuser::user.labels.password')}}</label>
            <div class="col-xs-8">
                <input id="password" type="password" class="form-control " name="password"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-4"
                   for="password-confirm">{{__('xuser::user.labels.confirm_password')}}</label>
            <div class="col-xs-8">
                <input id="password-confirm" type="password"
                       class="form-control validate[equals[password]] "
                       name="password_confirmation"
                       required>
            </div>
        </div>
        <br/>



    </form>
</div>