<?php

namespace App\Http\Controllers;

use App\Models\AppointmentType;
use Illuminate\Http\Request;

class AppointmentTypeOpenController extends Controller
{
    //

    public function getAppointmentTypes(){
        return AppointmentType::orderBy('appointment_type', 'asc')->get();
    }

}
