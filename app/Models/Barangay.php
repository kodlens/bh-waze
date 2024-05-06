<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $table = 'barangays';
    protected $primaryKey = 'id';


    protected $fillable = ['brgyCode', 'brgyRef', 'brgyDesc', 'regCode', 'provCode', 'citymunCode', 'active'];



}
