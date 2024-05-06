<?php

namespace App\Http\Controllers\Boarder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class BoarderDashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        return view('boarder.boarder-dashboard');
    }

    public function getUser(){
        if(Auth::user()->role == 'BOARDER'){
            $user = Auth::user();

            $user = \DB::table('users as a')
                ->leftJoin('provinces as b', 'a.province', 'b.provCode')
                ->leftJoin('cities as c', 'a.city', 'c.citymunCode')
                ->leftJoin('barangays as d', 'a.barangay', 'd.brgyCode')
                ->where('a.user_id', $user->user_id)
                ->first();

            return $user;
        }

        return [];
    }


}
