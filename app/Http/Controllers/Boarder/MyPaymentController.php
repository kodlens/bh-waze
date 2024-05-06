<?php

namespace App\Http\Controllers\Boarder;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyPaymentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('boarder');
    }


    public function index(){
        return view('boarder.my-payment');
    }


    public function getMyPayment(Request $req){
        $sort = explode('.', $req->sort_by);
        $user = Auth::user();
        $data = DB::table('payments as a')
            ->join('boarders as b', 'a.boarder_id', 'b.boarder_id')
            ->join('users as c', 'b.boarder_user_id', 'c.user_id')
            ->join('bedspaces as d', 'b.bedspace_id', 'd.bedspace_id')
            ->join('rooms as e', 'd.room_id', 'e.room_id')
            ->join('boarding_houses as f', 'e.bhouse_id', 'f.bhouse_id')
            ->where('c.user_id', $user->user_id)
            ->orderBy('a.'.$sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function getMyPaymentDetail($payment_id){
        return PaymentDetail::where('payment_id', $payment_id)
            ->orderBy('payment_detail_id', 'desc')
            ->get();
    }

    public function submitPaymentBill(Request $req, $payment_detail_id){


        $req->validate([
            'payment' => ['required'],
            //'receipt_img' =>  ['required', 'mimes:jpg,png,bmp', 'file', 'max:800'],
        ], $message = [
            'receipt_img.mimes' => 'Your uploaded image must be a file of jpg, png or bmp.',
        ]);


        $img = $req->file('receipt_img');
        if($img){
            $pathFile = $img->store('public/payment_receipt'); //get path of the file
            $n = explode('/', $pathFile); //split into array using /
        }
        $payment = Payment::find($req->payment_id);

//        if(Storage::exists('public/payment_receipt/' .$data->receipt_img)) {
//            Storage::delete('public/payment_receipt/' . $data->receipt_img);
//        }

        //get total balance
        $bal = ((double)$payment->balance - (double)$req->payment);
        //return $bal;


        if($bal == 0){
            $paymentStatus = 'PAID';
        }else{
            $paymentStatus = 'PARTIAL';
        }

        PaymentDetail::where('payment_detail_id', $payment_detail_id)
            ->update([
                'payment_id' => $req->payment_id,
                'amount_paid' => $req->payment,
                'balance' => $bal,
                'date_paid' => date('Y-m-d'),
                'receipt_img' => $img != null ? $n[2] : '',
                'payment_status'=> $paymentStatus
            ]);

        $payment->balance = $bal; 
        $payment->save();

//        $balance = $data->payment_to_pay - ((double)$data->payment + (double)$req->payment);
//
//        $data->payment = $req->payment;
//        $data->balance = $balance;
//        $data->receipt_img = $n[2];
//
//
//        $data->save();

        return response()->json([
            'status' => 'uploaded'
        ], 200);

    }

    public function getReceiptInfo($id){

        $data = DB::table('payments as a')
            ->join('payment_details',  'a.payment_id', 'payment_details.payment_id')
            ->join('boarders as b',  'a.boarder_id', 'b.boarder_id')
            ->join('users as c', 'b.boarder_user_id', 'c.user_id')
            ->join('bedspaces as d', 'b.bedspace_id','d.bedspace_id')
            ->join('boarding_houses as e', 'd.bhouse_id','e.bhouse_id')
            ->leftJoin('provinces as f', 'c.province','f.provCode')
            ->leftJoin('cities as g', 'c.city','g.citymunCode')
            ->leftJoin('barangays as h', 'c.barangay','h.brgyCode')
            ->where('payment_detail_id', $id)
            ->get();



        //return $data;
        $dateNow = date('Y-m-d');

        return view('boarder.my-payment-receipt')->with('data', $data[0])
            ->with('dateNow', $dateNow);
    }




}
