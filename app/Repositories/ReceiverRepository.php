<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\DB;

class ReceiverRepository
{
    /**
     * Get all receivers.
     *
     * @param User $user ;
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(User $user)
    {
        $users=DB::table('users')->join('messages',function ($join){
            $join->on('users.id','=','messages.from')->orOn('users.id','=','messages.to');
        })->where(function($query) use($user){
            $query->where('messages.to',$user->id)->orWhere('messages.from',$user->id);
        })
        ->where('users.id','!=',$user->id)
        ->select('users.*')->orderBy('messages.created_at','desc')->get()->unique()->toArray();
       
        return User::hydrate($users);
    }

    
}
