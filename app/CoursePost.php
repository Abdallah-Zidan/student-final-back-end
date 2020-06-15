<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoursePost extends Model
{
  protected $fillable = [
    'body', 'user_id', 'course_department_faculty_id'
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

  public function courseDepartmentFaculty()
  {
    return $this->belongsTo(CourseDepartmentFaculty::class);
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
}
