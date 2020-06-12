<?php

namespace App\Repositories;

use App\Message;
use App\User;

class MessageRepository
{

    public function getAll(User $receiver , User $user)
    {
        $messages = Message::where([['from', $user->id], ['to', $receiver->id]])
                    ->orWhere([['from', $receiver->id], ['to', $user->id]])
                    ->orderBy('created_at','desc')
                    ->paginate(10);
        return $messages;
    }
   

    /**
     * Create new Message;
     *
     * @param \App\User $receiver The message receiver.
     * @param array $data The rate data.
     * @param \App\User $receiver The message sender.
     *
     * @return Message
     */
    
    public function create(User $receiver, array $data ,User $sender)
    {
        return Message::create($data + [
            'from' => $sender->id,
            'to' => $receiver->id
        ]);
    }

}