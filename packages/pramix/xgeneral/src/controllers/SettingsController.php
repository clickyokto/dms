<?php

namespace Pramix\XGeneral\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XConfig\Models\XConfig;


class SettingsController extends Controller
{
    public function settingsPage()
    {
        return view('xgeneral::settings.settings');
    }

    public function store(Request $request)
    {
        parse_str($request['company_details'], $company_details);


        $config = XConfig::where('name', 'COMPANY_DETAILS')->first();

        $config->options_array = json_encode($company_details);

        $config->save();

        return response()->json(['status' => 'success', 'msg' => 'Success']);

    }


}
