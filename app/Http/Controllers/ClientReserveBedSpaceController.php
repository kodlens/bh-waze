<?php

namespace App\Http\Controllers;

use App\Models\BedSpace;
use App\Models\BookBedSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientReserveBedSpaceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function reserveBedspace($bedspace_id){


        $userid = Auth::user()->user_id;
        $ndate = date('Y-m-d');

        

        //you logic here...????
        //select if naa booking nag exist, if user naay prev booking
        $isExist = BookBedSpace::where('book_user_id', $userid)
            ->where('approval_status', '!=',  'CANCELLED')
            ->where('approval_status', '!=',  'FAILED')
            ->exists();

        //return $isExist;
            
        if($isExist){
            return response()->json([
                'errors'=> 'active'
            ], 422);
        }


        $bedspace = BedSpace::find($bedspace_id);
        $bedspace->is_booked = 1;
        $bedspace->save();

        BookBedSpace::create([
            'bedspace_id' => $bedspace_id,
            'book_user_id' => $userid,
            'book_date' => $ndate,
            'rental_price' => $bedspace->price
        ]);

        return response()->json([
            'status' => 'reserved'
        ]);

    }


}
