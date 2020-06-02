<?php

namespace App\Repositories;

use App\Tag;

class TagRepository
{
    public function create($name)
    {
        return Tag::create(['name' => $name]);
    }
}
