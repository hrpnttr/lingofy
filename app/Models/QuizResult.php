<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['student_id', 'quiz_id', 'answers', 'score'])]
class QuizResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_results';

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'student_id' => 'integer',
            'quiz_id' => 'integer',
            'score' => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
