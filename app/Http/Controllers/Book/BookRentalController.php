<?php

namespace App\Http\Controllers\Book;

use App\Book;
use App\Http\Controllers\ApiController;
use App\Rental;
use Illuminate\Http\Request;
class BookRentalController extends ApiController
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        $rentals = $book->rentals()->with('user')->get();

        return $this->showAll($rentals);
    }
}
