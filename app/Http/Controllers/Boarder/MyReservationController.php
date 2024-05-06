<?php

namespace App\Http\Controllers\Boarder;

use App\Http\Controllers\Controller;
use App\Models\BedSpace;
use App\Models\BookBedSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\String\s;

class MyReservationController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index(){
        return view('boarder.my-reservation');
    }

    public function getMyReservation(Request $req){
        $sort = explode('.', $req->sort_by);

        $userid = Auth::user()->user_id;
        $data = \DB::table('book_bedspaces as a')
            ->join('bedspaces as b', 'a.bedspace_id', 'b.bedspace_id')
            ->join('rooms as c', 'b.room_id', 'c.room_id')
            ->where('book_user_id', $userid)
            
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function uploadProofTransaction(Request $req, $book_bedspace_id){
        $req->validate([
            'proof_transaction' => ['required', 'mimes:jpg,png,bmp', 'max: 800']
        ], $message = [
            'proof_transaction.required' => 'Proof of transaction is required.',
            'proof_transaction.mimes' => 'Proof of transaction file type must type of jpg, png, bmp.',
            'proof_transaction.max' => 'Proof of transaction must lesser than 800Kb.'
        ]);


        $proofTransaction = $req->file('proof_transaction');

        if($proofTransaction){
            $pathFile = $proofTransaction->store('public/prooftrans'); //get path of the file
            $proof_transaction = explode('/', $pathFile); //split into array using /
        }

        $data = BookBedSpace::find($book_bedspace_id);

         if(Storage::exists('public/prooftrans/'. $data->proof_transaction)){
             Storage::delete('public/prooftrans/'. $data->proof_transaction);
         }

        $data->proof_transaction = $proof_transaction[2];
        $data->save();



        return response()->json([
            'status' => 'uploaded'
        ]);
    }


    public function cancelReservation($id){

        $data = BookBedSpace::find($id);
        $data->approval_status = 'CANCELLED';
        $data->save();

        $bedspace_id = $data->bedspace_id;
        $data = BedSpace::find($bedspace_id);
        $data->is_booked = 0;
        $data->save();

        return response()->json([
            'status' => 'cancelled'
        ], 200);
    }


}
