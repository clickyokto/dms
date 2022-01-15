<div class="form-group">
    @if($label!='')
    <label @if(isset($parameters_array['class']) && strpos($parameters_array['class'], 'validate[required]') !== false) class="required" @endif for="{{$name ?? ''}}">{{$label ?? ''}}</label>
    @endif
    {{Form::textarea($name, $value, $parameters_array)}}
</div>
