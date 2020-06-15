<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Http\Resources\Tutorialcollection;
use App\Http\Resources\TutorialResource;
use App\Repositories\TutorialRepository;
use App\Http\Resources\FileResource;
use App\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    private $repo;

    public function __construct(TutorialRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tutorials = $this->repo->getAll($request->tags);
        return  new Tutorialcollection($tutorials);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tutorial = $this->repo->create($request->only(['body', 'tags','files']), $request->user());
        return response([
            'data' => [
                'tutorial' => [
                    'id' => $tutorial->id,
                    'tags'=> TagResource::collection($tutorial->tags),
                    'files' => FileResource::collection($tutorial->files)
                ],
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function show(Tutorial $tutorial)
    {
        $tutorial->load([
            'user',
            'comments' => function ($query) {
                $query->orderBy('created_at');
            },
            'comments.user',
            'tags'
        ]);

        return response([
            'data' => [
                'question' => new TutorialResource($tutorial)
            ]
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        if ($request->user()->can('update', $tutorial)) {
            $tutorial = $this->repo->update($tutorial, $request->only(['body', 'tags','files']));
            return response([
                'data' => [
                    'tags' => TagResource::collection($tutorial->tags)
                ]
            ], 201);
        }
        return response([], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tutorial $tutorial)
    {
        if (request()->user()->can('delete', $tutorial)) {
            $this->repo->delete($tutorial);
            return response([], 204);
        }
        return response([], 403);
    }
}
