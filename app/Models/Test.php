<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function testAnswers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function setResult()
    {
        $this->result = $this->testAnswers()->where('correct', true)->count();

        $this->save();
    }

    public function score()
    {
        if (isset($this->result) && !is_null($this->result)) {
            return $this->result;
        }

        return $this->testAnswers()->where('correct', true)->count();
    }
}
