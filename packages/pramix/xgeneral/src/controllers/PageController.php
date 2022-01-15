<?php

namespace Pramix\XGeneral\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PageController extends Controller
{
    public function privacyPolicy()
    {
        return view('xgeneral::privacy_policy');
    }


    public function aboutUsPage()
    {
        return view('xgeneral::about_us');
    }
}
