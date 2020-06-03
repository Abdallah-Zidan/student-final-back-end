<?php

namespace App\Repositories;

use App\Enums\TagScope;
use App\Tag;
use Illuminate\Support\Facades\DB;

class TagRepository
{

    public function getAll($scope)
    {
        $tags_ids = [];
        if ($scope == TagScope::TOOL) {
            $tags_ids = DB::table('tag_tools')->pluck('tag_id');
        } else if ($scope == TagScope::QUESTION) {
            $tags_ids = DB::table('question_tags')->pluck('tag_id');
        }
        return Tag::find($tags_ids);
    }

    public function create($name)
    {
        return Tag::create(['name' => $name]);
    }
}
