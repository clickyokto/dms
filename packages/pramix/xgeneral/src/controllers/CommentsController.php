<?php

namespace Pramix\XGeneral\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pramix\XGeneral\Models\CommentsModel;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;


class CommentsController extends Controller
{

    public function getCommentsList( $ref_type = '' , $ref_id = '')
    {

        if ($ref_id == '') {
            $comments = [];
        } else {

            $comments = CommentsModel::where('ref_id', $ref_id)->with('user')->where('comment_type',$ref_type)->get();

        }


        return Datatables::of($comments)
            ->addColumn('user', function ($comments) {

                return $comments->user->username;
            })
            ->editColumn('created_at', function ($comments) {
             //   $created_at = new Carbon($comments->created_at)->toDateTimeString();
return $comments->created_at->toDateTimeString();
            })
            ->rawColumns(['comments'])
            ->make(true);
    }

    public function saveComment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $comment = New CommentsModel;
        $comment->comments = $request['comment'];
        $comment->ref_id = $request['ref_id'];
        $comment->comment_type = $request['ref_type'];
        //$comment->created_by = Auth::id();
        $comment->save();
        try{ $comment->save();
        }catch (\Exception $e)
        {
            return response()->json(['status' => 'error', 'msg' => __('common.messages.save_error')]);
        }
        return response()->json(['status' => 'success' ,'msg' => __('Comments Added')]);
    }




//    public static function getCommentsList($ref_id, $ref_type)
//    {
//
//        $comments = CommentsModel::where('ref_id', $ref_id)->with('user')->where('comment_type',$ref_type)->get();
//        if ($comments == NULL)
//            $comments = new CommentsModel();
//
//        if ($comments == 'B') {
//
//            $comments->ref_id = $ref_id;
//            $comments->address_type = $address_type;
//            $comments->user_type = $user_type;
//            $comments->address_line_1 = $address_details['business_street1'];
//
//
//        } elseif ($address_type == 'S') {
//
//            $address->ref_id = $ref_id;
//            $address->address_type = $address_type;
//            $address->user_type = $user_type;
//            $address->address_line_1 = $address_details['shipping_street1'];
//            $address->address_line_2 = $address_details['shipping_street2'];
//            $address->city_id = $address_details['shipping_city_id'];
//            $address->district_id = $address_details['shipping_district_id'];
//            $address->country = $address_details['shipping_country'];
//            $address->description = $address_details['shipping_remarks'];
//
//        }
//        $address->save();
//
//    }
}
