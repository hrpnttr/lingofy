<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['course_id', 'class_name', 'description', 'materials'])]
class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected function casts(): array
    {
        return [
            'materials' => 'array',
            'course_id' => 'integer',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
