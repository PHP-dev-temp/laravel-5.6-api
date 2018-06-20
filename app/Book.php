<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $hidden = ['pivot'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'author_id',
        'inventory_id',
    ];

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function rentals(){
        return $this->hasMany(Rental::class);
    }

}
