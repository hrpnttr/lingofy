<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['student_id', 'contents'])]
class Scourse extends Model
{
    use HasFactory;

    protected $table = 'scourses';

    protected function casts(): array
    {
        return [
            'contents' => 'array',
            'student_id' => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
