<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoarderPaymentController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('landowner');
    }

    public function index(){
        return view('landowner.boarder-payment');
    }

    public function getBoarderPayment(Request $req){
        $data = DB::table('payments as a')
            ->join('payment_details as b', 'a.payment_id', 'b.payment_id')
            ->join('boarders as c',  'a.boarder_id', 'c.boarder_id')
            ->join('users as d', 'c.boarder_user_id', 'd.user_id')
            ->join('bedspaces as e', 'c.bedspace_id','e.bedspace_id')
            ->join('boarding_houses as f', 'e.bhouse_id','f.bhouse_id')
            ->orderBy('a.payment_id', 'desc')
            ->where('d.lname', 'like', $req->lname . '%')
            ->where('e.bedspace_name', 'like', $req->bedspace . '%')
            ->get();

        return $data;
    }

    public function markPaid($id){

        $data = PaymentDetail::find($id);
        $data->payment_status = 'PAID';
        $data->save();

        return response()->json([
            'status' => 'paid'
        ]);
    }

    public function getReceiptInfo($id){

        $data = DB::table('payments as a')
            ->join('payment_details as b', 'a.payment_id', 'b.payment_id')
            ->join('boarders as c',  'a.boarder_id', 'c.boarder_id')
            ->join('users as d', 'c.boarder_user_id', 'd.user_id')
            ->join('bedspaces as e', 'c.bedspace_id','e.bedspace_id')
            ->join('boarding_houses as f', 'e.bhouse_id','f.bhouse_id')
            ->leftJoin('provinces as g', 'd.province','g.provCode')
            ->leftJoin('cities as h', 'd.city','h.citymunCode')
            ->leftJoin('barangays as i', 'd.barangay','i.brgyCode')
            ->where('a.payment_id', $id)
            ->get();

        //return $data;
        $dateNow = date('Y-m-d');
        
        return view('landowner.receipt-payment')->with('data', $data[0])
            ->with('dateNow', $dateNow);
    }


}
