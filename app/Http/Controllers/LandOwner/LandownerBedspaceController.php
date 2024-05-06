<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BedSpace;
use App\Models\BedspaceImg;
use Illuminate\Support\Facades\DB;
use Auth;

class LandownerBedspaceController extends Controller
{
    //

    public function __construct(){


        $this->middleware('auth');
        $this->middleware('landowner');
    }


    public function index($bhouse_id, $bh_room_id){

        $bedspaces = BedSpace::where('room_id', $bh_room_id)->get();
        return view('landowner.bedspace.boarding-house-bedspace')
            ->with('bhouse_id', $bhouse_id)
            ->with('bh_room_id', $bh_room_id)
            ->with('bedspaces', $bedspaces);
    }

//    public function getBedspaceImgs($room_id){
//
//        return DB::table('boarding_houses as a')
//            ->join('rooms as b', 'a.bhouse_id', 'b.bhouse_id')
//            ->join('bedspaces as c', 'b.room_id', 'c.room_id')
//            ->where('a.bhouse_id', $room_id)
//            ->get();
//    }



    public function showBedSpace($id){
        return BedSpace::find($id);
    }



    public function store(Request $req, $bhouse_id, $room_id){

        $req->validate([
            'bedspace_name' => ['required'],
            'bedspace_desc' => ['required'],
            'bedspace_img_path.*' => ['required', 'mimes:jpg,bmp,png', 'max:700'],
            'price' => ['required', 'integer'],

        ], $message = [
            'bedspace_img_path.mimes' => 'Image must be a jpeg, png or bmp.',
            'bedspace_img_path.max' => 'Image size must be atleast 700kb each.',

        ]);

        $bedspace = BedSpace::create([
            'room_id' => $room_id,
            'bhouse_id' => $bhouse_id,
            'bedspace_name' => strtoupper($req->bedspace_name),
            'bedspace_desc' => strtoupper($req->bedspace_desc),
            'price' => $req->price,
            'is_booked' => 0
        ]);

        $bedspace_id = $bedspace->bedspace_id;

        if($req->hasFile('bedspace_img_path')){
            foreach($req->file('bedspace_img_path') as $image){
                $pathFile = $image->store('public/bedspaces'); //get path of the file
                $n = explode('/', $pathFile); //split into array using /


                //insert into database after upload 1 image
                BedspaceImg::create([
                    'bedspace_id' => $bedspace_id,
                    'bedspace_img_path' => $n[2]
                ]);
            }
        }

        return response()->json([
            'status'=>'saved'
        ],200);

    }


    public function update(Request $req, $id){

        $req->validate([
            'bedspace_name' => ['required'],
            'bedspace_desc' => ['required'],
            'price' => ['required', 'integer'],
        ]);

        $data = BedSpace::find($id);
        $data->bedspace_name = strtoupper($req->bedspace_name);
        $data->bedspace_desc = strtoupper($req->bedspace_desc);
        $data->price = $req->price;
        $data->save();

        return response()->json([
            'status'=>'updated'
        ],200);

    }

    public function getBhBedspaces(Request $req, $id){
        $user_id = Auth::user()->user_id;
        $sort = explode('.', $req->sort_by);

        return DB::table('bedspaces as a')
            ->join('rooms as b', 'a.room_id', 'b.room_id')
            ->join('boarding_houses as c', 'b.bhouse_id', 'c.bhouse_id')
            ->where('b.room_id', $id)
            ->where('c.user_id', $user_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function destroy($id){
        BedSpace::destroy($id);
        return response()->json([
            'status'=>'deleted'
        ],200);
    }



}
