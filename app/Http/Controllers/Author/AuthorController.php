<?php

namespace App\Http\Controllers\Author;

use App\Author;
use App\Http\Controllers\ApiController;
use Faker\Provider\Image;
use Illuminate\Http\Request;

class AuthorController extends ApiController
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
        $autors = Author::all();

        return $this->showAll($autors);
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
        ];
        $this->validate($request, $rules);

        $data = $request->all();

        if (!$request->has('bio')){
            $data['bio'] = 'No biography';
        }

        if ($request->hasFile('image')){
            $data['image'] = $request->image->store('authors');
        } else {
            $data['image']  = 'authors/no_author_image.jpg';
        }

        $category = Author::create($data);

        return $this->showOne($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return $this->showOne($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        if ($request->has('name')) $author->name = $request->name;
        if ($request->has('bio')) $author->bio = $request->bio;
        if ($request->hasFile('image')) {
            $author->image = $request->image->store('authors');
        }

        $author->save();

        return $this->showOne($author);
    }
}
