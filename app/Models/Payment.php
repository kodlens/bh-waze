<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';


    protected $fillable = ['boarder_id', 'book_bedspace_id', 'balance', 'rental_price', 'date_pay', 'payment_status'];


}
