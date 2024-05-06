<?php

namespace App\Http\Controllers;

use App\Models\BedSpace;
use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\BookBedSpace;



class ClientBhouseRoomController extends Controller
{
    //

    public function index($bhouse_id){
        $rooms = Room::where('bhouse_id', $bhouse_id)
            ->get();

        return view('client-bh-rooms')
            ->with('data', $rooms);
    }


    public function getBhRoomsClient($bhouse_id){
        $rooms = Room::where('bhouse_id', $bhouse_id)
            ->get();

            return $rooms;
    }

    public function getBedSpaces($room_id){
        $dateNow = date('Y-m-d H:i:s');

        $date24 = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime(date('Y-m-d H:i:s'))));
        //subract 1 day from today;

        //return $dateNow;
        $rsrv = BookBedSpace::where('created_at', '<=', $date24)
            ->where('is_active', 1)
            ->where('approval_status', 'PENDING')
            ->get();

        //kigwa// tiwason ang mark failed
        //d makita kai walay bedspace Id ma forward tungod sa status na pending

        //scenario,, dapat ma mark as failed if dili kabayad 24hrs.
        //mabalik ug available ang bedspace then ang reservation ref ma mark as failed


        if(count($rsrv) > 0){
            //if naa
            BookBedSpace::where('created_at', '<=', $date24)
                ->where('is_active', 1)
                ->where('approval_status', 'PENDING')
                ->update([
                    'is_active' => 0,
                    'approval_status' => 'FAILED'
                ]);

            foreach($rsrv as $item){
                BedSpace::where('bedspace_id', '=', $item->bedspace_id)
                    ->update([
                        'is_booked' => 0
                    ]);
            }
        }

        $bedspaces = BedSpace::where('room_id', $room_id)
            ->with(['imgs'])
            ->where('is_booked', 0)
            ->get();
        return $bedspaces;
    }



}
