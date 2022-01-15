$(document).ready(function () {
    jQuery.fn.extend({
        imageAddToArray: function (value) {
            return this.filter("#media_ids").val(function (i, v) {
                var arr = [];

                if ($('#media_ids').val() != '')
                    arr = v.split(',');

                arr.push(value);

                return arr.join(',');
            }).end();
        },
        imageRemoveFromArray: function (value) {
            return this.filter("#media_ids").val(function (i, v) {
                return $.grep(v.split(','), function (val) {
                    return val != value;
                }).join(',');
            }).end();
        }
    });


    $(document).on('click', '.remove_media_btn', function (e) {

        var media_id = $(this).data("id");
        var li = $(this).closest("li");

        var delete_confirm = $.confirm({
            title: "Are you sure you want to permanently delete this file?",
            type: 'red',
            buttons: {
                delete: {
                    text: 'Delete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        e.preventDefault();
                        var params = {
                            media_id: media_id
                        };

                        $.ajax({
                            url: BASE + 'delete_media',
                            type: 'POST',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'error') {
                                    delete_confirm.close();
                                    notification(response);
                                } else {

                                    delete_confirm.close();
                                    $('#media_ids').imageRemoveFromArray(media_id);
                                    li.remove();
                                    notification(response);
                                    updateMediaOrder();

                                }
                            },
                            error: function (errors) {

                            }
                        });
                        e.preventDefault();
                        return false;
                    }
                },
                close: function () {
                }
            }
        });
        e.preventDefault();
    });


    var allowedExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    var uploader = new qq.FineUploader({
        element: document.getElementById("upload_media_process"),
        request: {
            endpoint: BASE + "media_upload",
            customHeaders: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        callbacks: {
            onComplete: function (id, name, responseJSON) {

                if (responseJSON.success) {
                    $('#media_ids').imageAddToArray(responseJSON.media_id)
                    $("#media_view_list").append('<li id="' + responseJSON.media_id + '" class="drag-item list-group-item list-group-item-success img-wrap"><button class="close btn hvr-buzz-out  btn btn-danger btn-xs"><i\n' +
                        '                                                           data-id="' + responseJSON.media_id + '" class="fa fa-remove remove_media_btn" aria-hidden="true"></i></button><a data-fancybox="gallery" href="' + BASE + 'uploads/' + responseJSON.folder_name + '/' + responseJSON.picture_name + '"><img width="100" src="' + BASE + 'uploads/' + responseJSON.folder_name + '/medium/' + responseJSON.picture_name + '"></a> </li>');
                    uploader.reset();
                    updateMediaOrder();
                }
            },
            onSubmit: function (id, fileName) {

                var newParams = {
                    media_type: $('#media_type').val(),
                    ref_id: $('#ref_id').val()
                };

                this.setParams(newParams);
            }
        },
        debug: false,
        autoUpload: true,
        multiple: false,
        validation: {
            allowedExtensions: allowedExtensions,
            sizeLimit: 2000000,

        }
    });


    function updateMediaOrder(){
       
        if ($('#ref_id').val() != '') {

            var params = {
                media_ids: $('#media_ids').val(),
                ref_id: $('#ref_id').val(),
                media_type: $('#media_type').val()
            };

            $.ajax({
                url: BASE + 'update_media_order',
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'error') {

                        notification(response);
                    } else {

                        notification(response);
                    }
                },
                error: function (errors) {

                }
            });
        }
    }


    dragula([
        document.getElementById('media_view_list'),

    ]).on('drop', function (el, target, source, sibling) {
        var media_ids = [];
        $('#media_view_list').find("li").each(function () {
            media_ids.push(this.id);
        });
        $('#media_ids').val(media_ids);

        updateMediaOrder();


    });



});