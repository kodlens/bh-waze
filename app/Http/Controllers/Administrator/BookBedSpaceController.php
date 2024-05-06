<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookBedSpaceController extends Controller
{
    //
    public function __construct()
    {
        # code...
        $this->middleware('auth');
        $this->middleware('admin');
    }


    
   
   
    
}
