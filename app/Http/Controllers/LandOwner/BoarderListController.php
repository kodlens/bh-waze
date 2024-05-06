<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\Boarder;
use App\Models\BookBedSpace;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoarderListController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('landowner');
    }


    public function index(){

        return view('landowner.boarder-list');
    }

    public function getBoarderList(Request $req){

        $sort = explode('.', $req->sort_by);
        $userid = Auth::user()->user_id;

        $data = DB::table('payments as a')
            ->join('boarders as b', 'a.boarder_id', 'b.boarder_id')
            ->join('bedspaces as c', 'b.bedspace_id', 'c.bedspace_id')
            ->join('rooms as d', 'c.room_id', 'd.room_id')
            ->join('boarding_houses as e', 'd.bhouse_id', 'e.bhouse_id')
            ->join('users as f', 'e.user_id', 'f.user_id')
            ->join('users as g', 'b.boarder_user_id', 'g.user_id')
            ->select('a.payment_id', 'a.boarder_id', 'b.boarder_user_id', 'a.book_bedspace_id', 'a.rental_price', 'a.date_pay', 'a.payment_status',
                'b.bedspace_id', 'b.date_acceptance', 'b.is_active',
                'c.room_id', 'c.bedspace_name', 'c.bedspace_desc', 'c.price', 'c.is_booked', 'c.is_active as bedspace_active',
                'd.room_no', 'd.room_desc', 'e.bhouse_id', 'e.bhouse_name', 'e.bhouse_desc',
                'f.lname', 'f.fname', 'f.mname', 'e.user_id', 'f.role', 'g.lname as b_lname', 'g.fname as b_fname', 'g.mname as b_mname')
            ->where('e.user_id', $userid)
            ->where('b.is_active', 1)
            ->where('c.bedspace_name', 'like',$req->bedspace . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function showPaymentDetails($id){
      

        return PaymentDetail::where('payment_id', $id)
            ->orderBy('payment_detail_id', 'desc')
            ->paginate(5);
    }


    public function removeBoarder($id){

        $data = Boarder::find($id);
        $data->is_active = 0;
        $data->save();

        return response()->json([
            'status' => 'removed'
        ],200);
    }
}
