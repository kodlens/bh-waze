<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


use Auth;

class LandownerBoardingHouseController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('landowner');

    }

    public function index(){
        return view('landowner.boarding-house');
    }

    public function show($id){

        return BoardingHouse::find($id);
    }


    public function getBhouses(Request $req){
        $id = Auth::user()->user_id;

        return BoardingHouse::where('user_id', $id)
            ->where('bhouse_name', 'like', $req->bhousename . '%')
            ->orderBy('bhouse_id', 'desc')
            ->get();
    }

    public function create(){
        return view('landowner.boarding-house-create');
    }

    public function store(Request $req){


        $validate = $req->validate([
            'bhouse_name' => ['required', 'string', 'max: 100'],
            'bhouse_rule' => ['required'],
            'bhouse_img_path' => ['required', 'mimes:jpg,png,bmp'],
            'long' => ['required'],
            'lat' => ['required'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ], $message = [
            'bhouse_img_path.mimes' => 'Type of the file must be jpg, png or bmp.',
            'bhouse_img_path.required' => 'Image is required.'
        ]);


        $userid = Auth::user()->user_id;

        $bhouseImg = null;
        //upload image b house
        $bhouseImg = $req->file('bhouse_img_path');
        if($bhouseImg){
            $pathFile = $bhouseImg->store('public/bhouses'); //get path of the file
            $bhousePath = explode('/', $pathFile); //split into array using /
        }

        BoardingHouse::create([
            'bhouse_name' => strtoupper($req->bhouse_name),
            'user_id' => $userid,
            'bhouse_rule' => $req->bhouse_rule,
            'bhouse_desc' => $req->bhouse_desc,
            'bhouse_img_path' => $bhousePath[2] != null ? $bhousePath[2]: '',
            'long' => $req->long,
            'lat' => $req->lat,
            'province' => strtoupper($req->province),
            'city' => strtoupper($req->city),
            'barangay' => strtoupper($req->barangay),
            'street' => strtoupper($req->street),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function edit($id){
        $bhouse = BoardingHouse::find($id);
        return view('landowner.boarding-house-create')
            ->with('bhouse', $bhouse)
            ->with('id', $id);
    }

    public function update(Request $req, $id){

        //return $req;
        $validate = $req->validate([
            'bhouse_name' => ['required', 'string', 'max: 100'],
            'bhouse_rule' => ['required', 'string'],
            'long' => ['required'],
            'lat' => ['required'],
        ]);

        $data = BoardingHouse::find($id);
        $bhouseImg = null;
        //upload image b house

        $bhouseImg = $req->file('bhouse_img_path');

        if($bhouseImg){
            //check the file and delete to update
            if(Storage::exists('public/bhouses/' .$data->bhouse_img_path)) {
                Storage::delete('public/bhouses/' . $data->bhouse_img_path);
            }
            $pathFile = $bhouseImg->store('public/bhouses'); //get path of the file
            $bhousePath = explode('/', $pathFile); //split into array using /
        }

        $data->bhouse_name = strtoupper($req->bhouse_name);
        $data->bhouse_rule = $req->bhouse_rule;
        $data->bhouse_desc = $req->bhouse_desc;

        if($bhouseImg){
            $data->bhouse_img_path = $bhousePath[2];
        }

        $data->long = strtoupper($req->long);
        $data->lat = strtoupper($req->lat);

        $data->province = strtoupper($req->province);
        $data->city = strtoupper($req->city);
        $data->barangay = strtoupper($req->barangay);
        $data->street = strtoupper($req->street);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }



    public function destroy($id){
        $data = BoardingHouse::find($id);
        if(Storage::exists('public/bhouses/' .$data->bhouse_img_path)) {
            Storage::delete('public/bhouses/' . $data->bhouse_img_path);
        }
        BoardingHouse::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }



}
