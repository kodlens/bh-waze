<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppointmentType;


class AdminDashboardController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');


    }

    public function index(){
        return view('administrator.admin-dashboard');
    }




}
