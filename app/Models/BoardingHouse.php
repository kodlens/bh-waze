<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingHouse extends Model
{
    use HasFactory;

    protected $table = 'boarding_houses';
    protected $primaryKey = 'bhouse_id';


    protected $fillable = ['bhouse_name', 'bhouse_desc',
    'user_id', 'owner', 'business_permit_imgpath',
    'bhouse_img_path', 'bhouse_rule', 'long', 'lat', 'is_approve', 'province', 'city', 'barangay', 'street'];
}
