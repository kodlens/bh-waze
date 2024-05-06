<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BoardingHouse;

class ClientBhouseController extends Controller
{
    //

    public function __construct(){

    }



    public function getBhouses(Request $req){
        $bhousename = $req->bhousename;


        $data = DB::table('boarding_houses as a')
            ->where('a.bhouse_name', 'like', $bhousename .'%')
            ->get();
        return $data;
    }


    public function showBhouseDetail($id){
        //$data = BoardingHouse::find($id);


        return view('client-bhouse-detail')->with('id', $id);
    }

    public function getBhouseDetail($id){
        $data = DB::table('boarding_houses as a')
            ->join('users as b', 'a.user_id', 'b.user_id')
            ->where('a.bhouse_id', $id)
            ->select('a.*', 'b.lname', 'b.fname', 'b.mname', 'b.contact_no')
            ->get();
        return $data[0];
    }
}
