<?php

namespace App\Http\Controllers\API\v1;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageCollection;
use App\Repositories\MessageRepository;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $repo;
    public function __construct(MessageRepository $repo)
    {
        $this->repo = $repo;
    }
    public function index(Request $request, $receiver)
    {
        $receiver = User::findOrFail($receiver);
        $messages = $this->repo->getAll($receiver, $request->user());
        return new MessageCollection($messages);
    }

    public function store(MessageRequest $request)
    {
        $receiver = User::findOrFail($request->receiver);
        $message = $this->repo->create($receiver, $request->only(['text']),$request->user());
        event(new MessageSent($message));
        return response(
            [
                'data' => [
                    'message' => [
                        'id' => $message->id,
                        'created_at' => $message->created_at,
                        'created_at_human' => $message->created_at->diffForHumans()
                    ]
                ]
            ],
            201
        );
    }
}
