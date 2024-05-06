<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;


class RequestAccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){

        $data = User::where('role', 'LANDOWNER')
            ->get();

        return view('administrator.request-accounts')
            ->with('data', $data); //blade.php
    }

    public function getRequestAccount(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = User::where('role', 'LANDOWNER')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
        return $data;
    }



    public function approveAccount($id){
        $data = User::find($id);
        $data->is_approve = 1;
        $data->save();


        return response()->json([
            'status' => 'approved'
        ],200);
    }

    public function disapproveAccount($id){
        $data = User::find($id);
        $data->is_approve = 0;
        $data->save();


        return response()->json([
            'status' => 'disapproved'
        ],200);
    }



}
