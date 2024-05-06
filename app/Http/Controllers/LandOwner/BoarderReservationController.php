<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\BedSpace;
use App\Models\Boarder;
use App\Models\BookBedSpace;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoarderReservationController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('landowner');
    }


    public function index(){
        $userid = Auth::user()->user_id;


        $data = DB::table('book_bedspaces as a')
            ->join('bedspaces as b', 'a.bedspace_id', 'b.bedspace_id')
            ->join('rooms as c', 'b.room_id', 'c.room_id')
            ->join('boarding_houses as d', 'c.bhouse_id', 'd.bhouse_id')
            ->join('users as e', 'a.book_user_id', 'e.user_id')
            ->select('a.book_bedspace_id', 'a.bedspace_id', 'a.book_user_id', 'a.book_date', 'a.rental_price', 'a.is_active', 'a.proof_transaction',
                'a.approval_status', 'b.room_id', 'c.bhouse_id', 'b.bedspace_name',
                'b.bedspace_desc', 'b.price', 'b.is_booked', 'c.room_no', 'c.room_desc', 'e.lname', 'e.fname', 'e.mname', 'e.user_id', 'e.role')
                ->where('d.user_id', $userid)
                ->get();

        return view('landowner.boarder-reservation')
            ->with('data', $data);
    }

    public function getBoarderReservation(Request $req){

        $sort = explode('.', $req->sort_by);
        $userid = Auth::user()->user_id;

        $data = DB::table('book_bedspaces as a')
            ->join('bedspaces as b', 'a.bedspace_id', 'b.bedspace_id')
            ->join('rooms as c', 'b.room_id', 'c.room_id')
            ->join('boarding_houses as d', 'c.bhouse_id', 'd.bhouse_id')
            ->join('users as e', 'a.book_user_id', 'e.user_id')
            ->select('a.book_bedspace_id', 'a.bedspace_id', 'a.book_user_id', 'a.book_date', 'a.rental_price', 'a.is_active', 'a.proof_transaction',
                'a.approval_status', 'b.room_id', 'c.bhouse_id', 'b.bedspace_name',
                'b.bedspace_desc', 'b.price', 'b.is_booked', 'c.room_no', 'c.room_desc', 'e.lname', 'e.fname', 'e.mname', 'e.user_id', 'e.role')
            ->where('d.user_id', $userid)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function approvedReservation(Request $req, $book_bedspace_id){

        $req->validate([
            'nstart_date' => ['required']
        ]);
        //nstart_date
        $date =  $req->nstart_date;
        $nstart_date = date("Y-m-d", strtotime($date));


        $data = BookBedSpace::find($book_bedspace_id);
        $data->approval_status = 'APPROVED';
        $data->save();

        $ndate = date('Y-m-d');

        $qr_code = substr(md5(time() . $data->book_user_id), -8);

        $boarder = Boarder::updateOrCreate(['boarder_user_id' => $data->book_user_id, 'bedspace_id' => $data->bedspace_id, 'is_active' => 1],
            [
                'qr_ref' => $qr_code,
                'boarder_user_id' => $data->book_user_id,
                'bedspace_id' => $data->bedspace_id,
                'book_bedspace_id' => $book_bedspace_id,
                'rental_price' => $data->rental_price,
                'date_acceptance' => $nstart_date,
            ]
        );

        Payment::create([
            'boarder_id' => $boarder->boarder_id,
            'book_bedspace_id' => $data->book_bedspace_id,
            'rental_price' => $data->rental_price,
            'date_pay' => $ndate,
            'balance' => 0,
        ]); //replace with boarder list

        return response()->json([
            'status' => 'approved'
        ],200);
    }


    public function cancelReservation($id){

        $bookBedspace = BookBedSpace::find($id);
        $bookBedspace->approval_status = 'CANCELLED';
        $bookBedspace->save();

        $boarder = Boarder::where('book_bedspace_id', $id)
            ->update([
                'is_active' => 0
            ]);


        $data = BedSpace::where('bedspace_id', $bookBedspace->bedspace_id)
            ->update([
                'is_booked' => 0
            ]);


        return response()->json([
            'status' => 'cancelled'
        ],200);
    }


}
