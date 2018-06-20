<?php

namespace App\Http\Controllers\Author;

use App\Author;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class AuthorBookController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Author $author)
    {
        $books = $author->books()->with('categories')->get();

        return $this->showAll($books);
    }
}
