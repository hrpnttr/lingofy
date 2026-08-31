<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'level', 'language', 'price', 'duration_hours', 'tags', 'published'])]
class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'published' => 'boolean',
            'price' => 'decimal:2',
            'duration_hours' => 'integer',
        ];
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'course_id');
    }
}
