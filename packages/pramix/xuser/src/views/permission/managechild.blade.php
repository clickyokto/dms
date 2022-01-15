<ul class="list-group no-margin">
@foreach($childs as $child)
<li class="list-group-item">
            
    <a href="{{url('/permissions/'.$child->id.'/edit')}}">  -{{ $child->display_name }} </a><span class="permission_name">{{$child->name}}</span>
	@if(count($child->childs))
            @include('xuser::permission.managechild',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>