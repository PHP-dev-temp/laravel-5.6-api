<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable=[
        'quantity',
    ];

    public function book(){
        return $this->hasOne(Book::class);
    }
}
