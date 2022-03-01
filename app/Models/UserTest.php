<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function validTimeFrame()
    {
        if (!is_null($this->start_date) && !is_null($this->end_date)) {
            return $this->start_date <= Carbon::now() && $this->end_date > Carbon::now();
        }

        return true;
    }
}