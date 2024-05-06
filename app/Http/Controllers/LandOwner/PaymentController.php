<?php

namespace App\Http\Controllers\LandOwner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('landowner');
    }


    public function store(Request $req){
        //submit bill

        $date =  $req->npayment_date;
        $ndate = date("Y-m-d", strtotime($date));
        //$ndate = date("m", strtotime($date));

        //filter date first
        $dateexist = PaymentDetail::where('date_pay', $ndate)->exists();
        if($dateexist){
            return response()->json([
                'errors' => [
                    'payment_date' => ['Bill already sent for this month.']
                ]
            ], 422);
        }



        $payment = Payment::where('payment_id', $req->payment_id)->first();
        $bal = $payment->balance;

     
       
        if($bal == 0){
            $bal = (double)$payment->rental_price;
            $payment->balance = $bal;
            $payment->save();
        }else{
            $bal = (double)$payment->balance + (double)$req->amount_to_pay;
            $payment->balance = $bal;
            $payment->save();
        }



        PaymentDetail::create([
            'payment_id' => $req->payment_id,
            'rental_price' => $req->amount_to_pay,
            'balance' => $bal,
            'date_pay' => $ndate,
            'payment_status' => 'UNPAID'
        ]);

//        PaymentDetail::updateOrCreate([
//            'date_pay' => $ndate,
//            'boarder_id' => $req->boarder_id,
//        ],[
//            'boarder_id' => $req->boarder_id,
//            'book_bedspace_id' => $req->book_bedspace_id,
//            'rental_price' => $req->amount_to_pay,
//            'date_pay' => $ndate,
//            'payment_status' => 'UNPAID'
//        ]);


        return response()->json([
            'status' => 'saved'
        ],200);
    }


}
