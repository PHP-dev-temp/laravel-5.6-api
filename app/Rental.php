<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    const RENT_STATUS = 'rent';
    const COMPLETE_STATUS = 'complete';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'end_date',
        'user_id',
        'book_id',
    ];

    public function book(){
        return $this->belongsTo(Book::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
