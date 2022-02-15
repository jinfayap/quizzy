<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array'
    ];

    public function getAnswerAttribute($value)
    {
        if (in_array($this->question_type, ['checkbox', 'radio', 'select'])) {
            return json_decode($value);
        }

        return $value;
    }
}