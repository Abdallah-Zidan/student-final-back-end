<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagCollection;
use App\Repositories\TagRepository;

class TagController extends Controller
{
    /**
     * The tag repository object.
     *
     * @var \App\Repositories\TagRepository
     */
    private $repo;

    /**
     * Create a new TagController object.
     *
     * @param \App\Repositories\TagRepository $repo The tag repository object.
     */
    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get all tags.
     *
     * @param \App\Http\Requests\TagRequest $request The request object.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TagRequest $request)
    {
        $tags = $this->repo->getAll($request->scope);

        return new TagCollection($tags);
    }
}