<?php

namespace App\Http\Controllers\User;

use App\Book;
use App\Http\Controllers\ApiController;
use App\Rental;
use App\User;
use Illuminate\Http\Request;

class UserRentalController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('is_customer');
        $this->middleware('is_admin', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        if (!$this->CheckPermission($user)){
            return($this->errorResponse('Unauthenticated!', 401));
        }
        $rentals = $user->rentals()->with('book')->get();

        return $this->showAll($rentals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $rules = [
            'book' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ];
        $this->validate($request, $rules);

        if(!Book::find($request->book)){
            return $this->errorResponse('Selected book does not exist!', 404);
        }

        $rental = Rental::create([
            'status' => Rental::RENT_STATUS,
            'user_id' => $user->id,
            'book_id' => $request->book,
            ]);

        return $this->showOne($rental);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @param Rental $rental
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Rental $rental)
    {

        if($rental->status === Rental::COMPLETE_STATUS){
            return $this->errorResponse('Rental is already completed!', 409);
        }

        $rental->update([
            'status' => Rental::COMPLETE_STATUS,
            'end_date' => date("Y-m-d H:i:s"),
            ]);

        return $this->showOne($rental);
    }
}
