<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookBedSpace extends Model
{
    use HasFactory;

    protected $table = 'book_bedspaces';
    protected $primaryKey = 'book_bedspace_id';


    protected $fillable = ['bedspace_id', 'book_user_id', 'book_date', 'occupy_date', 'rental_price', 'approval_status', 'is_active'];

    public function bedspace(){
        return $this->hasOne(BedSpace::class, 'bedspace_id', 'bedspace_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'book_user_id');
    }

}
