<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    protected $fillable = [
        'body', 'user_id'
    ];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($coursePost) {
            $coursePost->comments()->delete();
            $coursePost->files->each->delete();
        });
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * One-to-many relationship to the comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     *
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'parentable');
    }

    /**
     * One-to-many relationship to the files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     *
     */
    public function files()
    {
        return $this->morphMany(File::class, 'resourceable');
    }

    /**
     * Many-to-many relationship to the tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_tutorials')->withTimestamps();
    }
}
