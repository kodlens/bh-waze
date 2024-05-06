<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BHRequestController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

    }

    public function index(){
        return view('administrator.bh-request.bh-request');
    }

    public function getBHRequests(Request $req){

        $id = Auth::user()->user_id;

        $data = DB::table('boarding_houses as a')
            ->join('users as b', 'a.user_id', 'b.user_id')
            ->where('b.lname', 'like', $req->lname . '%')
            ->where('b.fname', 'like', $req->fname . '%')
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return BoardingHouse::find($id);

    }

    public function approveBh($id){
        $data = BoardingHouse::find($id);
        $data->is_approve = 1;
        $data->save();

        return response()->json([
            'status' => 'approved'
        ],200);

    }

    public function deactivateBh($id){
        $data = BoardingHouse::find($id);
        $data->is_approve = 0;
        $data->save();

        return response()->json([
            'status' => 'deactivated'
        ],200);
    }



}
