<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boarder extends Model
{
    use HasFactory;


    protected $table = 'boarders';
    protected $primaryKey = 'boarder_id';


    protected $fillable = ['qr_ref', 'boarder_user_id', 'bedspace_id', 'book_bedspace_id', 'date_acceptance','rental_price', 'active'];

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'boarder_user_id');
    }

    public function bedspace(){
        return $this->hasOne(User::class, 'bedspace_id', 'bedspace_id');
    }


}
