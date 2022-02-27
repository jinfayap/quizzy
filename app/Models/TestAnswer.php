<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'correct' => 'boolean'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function markAsCorrect()
    {
        $this->correct = true;

        $this->save();
    }
}
