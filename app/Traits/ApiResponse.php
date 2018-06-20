<?php

namespace App\Traits;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

trait ApiResponse{
    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['errors' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200){
        $collection = $this->sortData($collection);
        //$collection = $this->cacheResponse($collection);
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $model, $code = 200){
        return response()->json(['data' => $model], $code);
    }

    protected function sortData(Collection $collection){
        if(request()->has('sort_by')){
            $attribute = request()->sort_by;
            $sorted = $collection->sortBy($attribute);
            $collection = $sorted->values()->all();
        }

        return $collection;
    }

//    protected function cacheResponse($data){
//        $url = request()->url();
//
//        return Cache::remember($url, 1, function() use($data){
//            return $data;
//        });
//    }

    public function CheckPermission($model_user){
        $auth_user = $user = Auth::guard('api')->user();
        $return = array();
        if(!$auth_user) return $return;
        if($auth_user->role === USER::USER_CUSTOMER && $auth_user->id === $model_user->id) $return[] = USER::USER_CUSTOMER;
        if($auth_user->role === USER::USER_ADMIN) $return[] = USER::USER_ADMIN;
        return $return;
    }
}