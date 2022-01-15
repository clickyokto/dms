<?php

namespace Pramix\XMedia\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XMedia\Models\MediaModel;
use Pramix\XMedia\Models\MediaSortModel;

class MediaController extends Controller
{
    public function uploadMedia(Request $request)
    {

        $media_type = $request['media_type'];
        $ref_id = $request['ref_id'];

        if ($request->hasFile('qqfile')) {
            $file = $request->file('qqfile');
            $media = MediaModel::uploadMedia($file, $media_type, $media_type, $ref_id);

            return $media;
        }
    }

    public function deleteMedia(Request $request)
    {
        $media_id = $request['media_id'];
        MediaModel::deleteMediabyId($media_id);
        return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);

    }

    public function updateMediaOrder(Request $request)
    {
        $media_ids_list = $request['media_ids'];
        $ref_id = $request['ref_id'];
        $media_type = $request['media_type'];

        $media_order = MediaSortModel::where('ref_id', $ref_id)->where('media_type', $media_type)->first();

        if ($media_order == NULL) {
            MediaModel::saveMediaOrder($media_ids_list, $media_type, $ref_id);
            return response()->json(['status' => 'success']);
        } else {
            $media_order->image_order = $media_ids_list;
            $media_order->save();
        }
        return response()->json(['status' => 'success']);


    }
}
