<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['full_name', 'phone', 'email', 'password'])]
#[Hidden(['password'])]
class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getCreatedAtAttribute()
    {
        $val = $this->attributes['createdAt'] ?? ($this->attributes['created_at'] ?? null);
        return $val ? \Illuminate\Support\Carbon::parse($val) : null;
    }

    public function getUpdatedAtAttribute()
    {
        $val = $this->attributes['updatedAt'] ?? ($this->attributes['updated_at'] ?? null);
        return $val ? \Illuminate\Support\Carbon::parse($val) : null;
    }
}
