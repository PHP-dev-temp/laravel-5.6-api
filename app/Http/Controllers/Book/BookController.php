<?php

namespace App\Http\Controllers\Book;

use App\Author;
use App\Book;
use App\Http\Controllers\ApiController;
use App\Inventory;
use Illuminate\Http\Request;

class BookController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('is_admin', ['only' => ['store', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();

        return $this->showAll($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'author_id' => 'required|integer',
            'image' => 'image',
            'quantity' => 'required|integer|min:1'
        ];
        $this->validate($request, $rules);

        $data = $request->all();

        if(!Author::find($data['author_id'])){
            return $this->errorResponse('Author does not exist! Create the author first.', 409);
        }

        $inventory = Inventory::create(['quantity' => $data['quantity']]);
        $data['inventory_id'] = $inventory->id;

        if ($request->hasFile('image')){
            $data['image'] = $request->image->store('books');
        } else {
            $data['image'] = 'books/no_book_image.jpg';
        }

        if (!$request->has('description')){
            $data['description'] = 'No description';
        }

        $book = Book::create($data);

        $book_id = $book->id;
        $book = Book::where('id', $book_id)->with('inventory')->with('author')->first();

        return $this->showOne($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        $book_id = $book->id;
        $book = Book::where('id', $book_id)->with('inventory')->with('author')->with('categories')->first();

        return $this->showOne($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $rules = [
            'author_id' => 'integer',
            'image' => 'image',
            'quantity' => 'integer|min:1'
        ];
        $this->validate($request, $rules);

        if($request->has('name')) $book->name = $request->name;
        if($request->hasFile('image')) {
            $book->image = $request->image->store('books');
        }
        if($request->has('author_id')) $book->author_id = $request->author_id;
        if($request->has('description')) $book->description = $request->description;
        if($request->has('quantity')) {
            Inventory::find($book->inventory_id)->update(['quantity' => $request->quantity]);
        }

        if($request->has('categories')){
            $cats = explode(",",$request->categories);
            $book->categories()->sync($cats);
        }

        $book->save();

        $book_id = $book->id;
        $book = Book::where('id', $book_id)->with('inventory')->with('author')->with('categories')->first();


//        $books = Book::all();
//        foreach($books as $book){
//            $book->inventory_id = $book->id;
//            $book->save();
//        }
        return $this->showOne($book);
    }
}
