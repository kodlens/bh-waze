<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedspaceImg extends Model
{
    use HasFactory;

    protected $table = 'bedspace_imgs';
    protected $primaryKey = 'bedspace_img_id';

    protected $fillable = ['bedspace_id','bedspace_img_path'];

    public function bedspace(){
        return $this->hasOne(BedSpace::class, 'bedspace_id', 'bedspace_id');
    }

}
