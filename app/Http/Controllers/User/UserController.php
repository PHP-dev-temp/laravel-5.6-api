<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('is_customer');
        $this->middleware('is_admin', ['except' => ['show', 'getUserByEmail']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ];
        $this->validate($request, $rules);

        $data = $request->all();

        $data['role'] = User::USER_CUSTOMER;

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if (!$this->CheckPermission($user)){
            return($this->errorResponse('Unauthenticated!', 401));
        }
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
        ];
        $this->validate($request, $rules);

        if($request->has('name')) $user->name = $request->name;
        if($request->hasFile('email')) $user->email = $request->email;
        if($request->has('password')) $user->password = Hash::make($request->password);
        if($request->has('address')) $user->address = $request->address;
        if($request->has('city')) $user->city = $request->city;
        if($request->has('phone')) $user->phone = $request->phone;
        if($request->has('role')) {
            if ($request->role === User::USER_ADMIN) {
                $user->role = User::USER_ADMIN;
            }else{
                $user->role = User::USER_CUSTOMER;
            }
        }

        $user->save();
        return $this->showOne($user);
    }

    public function getUserByEmail($email) {
        $user = User::where('email', $email)-> first();;
        if (!$user){
            return($this->errorResponse('UNot found!', 404));
        }
        if (Auth::guard('api')->user()->id !== $user->id) {
            return($this->errorResponse('Unauthenticated!', 401));
        }
        return $this->showOne($user);
    }
}
