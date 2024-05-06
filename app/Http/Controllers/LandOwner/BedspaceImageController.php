<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\BedspaceImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BedspaceImageController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('landowner');
    }


    public function index($bhId, $roomId, $bedspaceId){
        return view('landowner.bedspace.bedspace-images')
            ->with('bhId', $bhId)
            ->with('roomId', $roomId)
            ->with('bedspaceId', $bedspaceId);
    }

    public function getImages($bedspace_id){
        $data = BedspaceImg::where('bedspace_id', $bedspace_id)
            ->get();

        return $data;
    }

    public function store(Request $req, $bedspaceId){
        if($req->hasFile('bedspace_img_path')) {
            foreach ($req->file('bedspace_img_path') as $image) {
                $pathFile = $image->store('public/bedspaces'); //get path of the file
                $n = explode('/', $pathFile); //split into array using /


                //insert into database after upload 1 image
                BedspaceImg::create([
                    'bedspace_id' => $bedspaceId,
                    'bedspace_img_path' => $n[2]
                ]);
            }
        }

        return response()->json([
            'status' => 'saved'
        ],200);
    }



    public function destroy($id){

        $data = BedspaceImg::find($id);

        if(Storage::exists('public/bedspaces/' .$data->bedspace_img_path)) {
            Storage::delete('public/bedspaces/' . $data->bedspace_img_path);
        }

        BedspaceImg::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ],200);

    }

}
