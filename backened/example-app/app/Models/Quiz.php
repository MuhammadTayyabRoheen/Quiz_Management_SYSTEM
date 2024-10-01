<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Quiz extends Model
{
    protected $fillable = ['title', 'description', 'schedule_date', 'expiration_date', 'status'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'quiz_assignments')->withTimestamps();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function questions()
{
    return $this->hasMany(Question::class);
}



}
