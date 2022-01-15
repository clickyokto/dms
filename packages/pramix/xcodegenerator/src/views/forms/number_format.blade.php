<div class="form-group">
    <label @if(isset($parameters_array['class']) && strpos($parameters_array['class'], 'validate[required]') !== false) class="required" @endif for="{{$name ?? ''}}">{{$label ?? ''}}</label>
    {{Form::number($name, $value, $parameters_array)}}
</div>