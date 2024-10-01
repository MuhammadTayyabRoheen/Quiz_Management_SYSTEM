<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'score', 'results','video_path', 'status'];

      // Add this block to cast 'results' as JSON or an array
      protected $casts = [
        'results' => 'array',  // Or use 'json' depending on your use case
    ];
    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

}

