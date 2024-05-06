<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;


    protected $table = 'payment_details';
    protected $primaryKey = 'payment_detail_id';

    protected $fillable = ['payment_id',
        'rental_price',
        'amount_paid',
        'balance',
        'date_pay',
        'payment_status',
        'receipt_img',
        'date_paid'];
}
