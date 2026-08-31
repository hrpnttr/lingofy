<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['class_id', 'title', 'description', 'type', 'contens'])]
class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected function casts(): array
    {
        return [
            'contens' => 'array',
            'class_id' => 'integer',
        ];
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
