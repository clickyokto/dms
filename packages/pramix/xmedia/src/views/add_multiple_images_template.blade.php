<input type="hidden" id="media_ids" name="media_ids"
       value="{{$media_order ?? ''}}">
<div id="upload_media_process"></div>

<ul class="media_view_list list-inline"
    id="media_view_list">

    @if(isset($media_array))
        @foreach($media_array as $media)

            <li id="{{$media->id}}"
                class="drag-item list-group-item list-group-item-success img-wrap">
                <button
                        class="close btn hvr-buzz-out  btn btn-danger btn-xs">
                    <i data-id="{{$media->id}}"
                       class="fa fa-remove remove_media_btn"
                       aria-hidden="true"></i></button>
                <a data-fancybox="gallery"
                   href="{{url('uploads/' . $media->folder_name . '/' . $media->file_name)}}">
                    <img width="100"
                         src="{{url('uploads/' . $media->folder_name . '/medium/' . $media->file_name)}}">
                </a>
            </li>
        @endforeach
    @endif

</ul>
