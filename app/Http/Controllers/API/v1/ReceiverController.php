<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiverCollection;
use App\Http\Resources\ReceiverResource;
use App\Message;
use App\Repositories\ReceiverRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiverController extends Controller
{
    private $repo;

    public function __construct(ReceiverRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $user=$request->user();
        $users=$this->repo->getAll($user);
        return new ReceiverCollection($users);   
    }
}
