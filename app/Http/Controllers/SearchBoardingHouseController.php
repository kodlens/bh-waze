<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchBoardingHouseController extends Controller
{
    //

    public function index(){
        return view('search-boarding-houses');
    }
}
