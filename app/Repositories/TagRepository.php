<?php

namespace App\Repositories;

use App\Enums\TagScope;
use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TagRepository
{
    /**
     * Get all tags.
     *
     * @param int $type The tag scope.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(int $scope)
    {
        $tags = new Collection;

        if ($scope === TagScope::TOOL)
            $tags = DB::table('tag_tools')->get();
        else if ($scope === TagScope::QUESTION)
            $tags = DB::table('question_tags')->get();

        return Tag::find($tags->pluck('tag_id'));
    }

    /**
     * Create a tag.
     *
     * @param array $data The tag data.
     *
     * @return \App\Tag
     */
    public function create(array $data)
    {
        return Tag::create($data);
    }
}