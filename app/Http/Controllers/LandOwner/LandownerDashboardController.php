<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\BoardingHouse;


class LandownerDashboardController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('landowner');

    }

    public function index(){

        return view('landowner.landowner-dashboard');
    }


    public function getLandOwnerBhouses(){
        $user = Auth::user();

        $bhouses = BoardingHouse::join('users', 'boarding_houses.user_id', 'users.user_id')
            ->where('boarding_houses.user_id', $user->user_id)
            ->get();

        return $bhouses;
    }



}
