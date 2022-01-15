<?php

namespace Pramix\XCommunication\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pramix\XCommunication\Models\TemplateModel;
use Auth;
use Illuminate\Support\Facades\Validator;
use Datatables;

class TemplateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type) {
        return view('xcommunication::templates.templates_list')->with('type',$type);
    }

    public function getTemplatesList($template_type) {
        $type = '';
        $type_url = '';
        if($template_type == 'sms')
            $type = 'S';
        else if($template_type == 'email')
            $type = 'E';
        $product = TemplateModel::select('id','template_name','description','type')
                ->where('type', $type)
                ->orderBy('created_at', 'desc')
                ->get();

        return Datatables::of($product)
                        ->addColumn('action', function ($product) {

                            if($product->type == 'E')
                                $type_url = 'email';
                            else
                                $type_url = 'sms';

                            $buttons = '<a id="edit_template" class="btn btn-info btn-xs" href="' . url("templates/".$type_url."/" . $product->id . "/edit") . '"  data-toggle="tooltip" data-placement="left" title="" data-original-title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';

                           // $buttons .= '<button id="delete_template" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fas fa-trash" aria-hidden="true"></i></button>';

                            return $buttons;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = NULL) {
        if ($type != NULL) {

            $shortcodes = array_keys(config('xcommunication.shortcodes'));
            $shortcodes = implode(" , ", $shortcodes);



            if ($type == 'email') {
                return view("xcommunication::templates.create_template")->with('type', $type)->with('shortcodes',$shortcodes);
            } else if ($type == 'sms') {
                return view("xcommunication::templates.create_template")->with('type', $type)->with('shortcodes',$shortcodes);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        parse_str($request['template_details'], $template_details); //This will convert the string to array

        $validator = Validator::make($template_details, [
                    'template_name' => 'required',
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }
         $editor_content = $request['editor_content'];
        $message = $request['message'];

        if ($request['type'] == 'sms')
            $type = 'S';
        elseif ($request['type'] == 'email')
            $type = 'E';

        $template = new TemplateModel();
        $template->template_name = $template_details['template_name'];
        $template->description = $template_details['description'];
        $template->content = $message;
         //$template->editor_content = $editor_content;
        $template->type = $type;

        try {
            $template->save();
            return response()->json(['status' => 'success','msg' => __('xcommunication::common.messages.save_successfully'), 'id' => $template->id]);
        }
        catch (\Exception $e) {
        dd($e->getMessage());
            return response()->json(['status' => 'error','msg' => __('common.messages.save_error')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($type, $id) {

        $template = TemplateModel::find($id);
          $shortcodes = array_keys(config('xcommunication.shortcodes'));
            $shortcodes = implode(" , ", $shortcodes);
        return view('xcommunication::templates.create_template')->with('type',$type)->with('template_details',$template)->with('shortcodes',$shortcodes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        parse_str($request['template_details'], $template_details); //This will convert the string to array

        $validator = Validator::make($template_details, [
                    'template_name' => 'required',
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }
        $editor_content = $request['editor_content'];
        $message = $request['message'];

        if ($request['type'] == 'sms')
            $type = 'S';
        elseif ($request['type'] == 'email')
            $type = 'E';

        $template = TemplateModel::find($id);
        $template->template_name = $template_details['template_name'];
        $template->description = $template_details['description'];
        $template->content = $message;
      //  $template->editor_content = $editor_content;
        $template->type = $type;
        try {
            $template->save();
            return response()->json(['status' => 'success','msg' => __('xcommunication::common.messages.save_successfully'), 'id' => $template->id]);
        }
        catch (\Exception $e) {
        //dd($e->getMessage());
            return response()->json(['status' => 'error','msg' => __('common.messages.save_error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $template =  TemplateModel::find($id);
        if($template->delete())
            return response()->json(['status' => 'success', 'msg' => __('emarketing::common.messages.record_deleted')]);
        else
            return response()->json(['status' => 'error', 'msg' => __('emarketing::common.errors.can_not_delete_record_used_somewhere')]);
    }

    public function getEmailEditor($template_id = NULL) {
        $editor_content = '';

        if($template_id!=NULL){
        $template = TemplateModel::find($template_id);
               $editor_content = $template->editor_content;
        }
        return view("emarketing::templates.email_editor")->with('editor_content',$editor_content);
    }

    public function uploadMedia(Request $request) {

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = str_replace(" ", "_", $file->getClientOriginalName());

            if (file_exists('public/uploads/emarketing/' . $file_name)) {
                $file_name = rand(10, 100) . $file_name;
            } else {
                $file_name = $file_name;
            }

            $file->move('public/uploads/emarketing', $file_name);

            $media = new MediaModel();
            $media->media_name = $file_name;
            $media->save();

            return array('success' => true, 'picture_name' => $file_name);
        }
    }

    public function getMedia() {

        $media = MediaModel::where('created_by', Auth::id())->pluck('media_name')->toArray();

        //creating response
        $response = array();

        $response['code'] = 0;
        $response['files'] = $media;
        $response['directory'] = url('/public/uploads/emarketing').'/';


        return json_encode($response);
    }

}
