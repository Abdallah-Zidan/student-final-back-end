<?php

namespace App\Repositories;

use App\Enums\TagScope;
use App\Tag;
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
    public function getAll($scope)
    {
        $tags_ids = [];

        if ($scope == TagScope::TOOL) 
        {
            $tags_ids = DB::table('tag_tools')->pluck('tag_id');
        } else if ($scope == TagScope::QUESTION) 
        {
            $tags_ids = DB::table('question_tags')->pluck('tag_id');
        }
        return Tag::find($tags_ids);
    }

    /**
     * Create a tag.
     *
     * @param array $data The tag data.
     *
     * @return \App\Tag
     */
    public function create($data)
    {
        return Tag::create($data);
    }
}
