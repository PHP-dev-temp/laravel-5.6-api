<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = ['pivot'];

    protected $fillable=[
        'name',
        'description',
    ];

    public function books(){
        return $this->belongsToMany(Book::class);
    }
}
