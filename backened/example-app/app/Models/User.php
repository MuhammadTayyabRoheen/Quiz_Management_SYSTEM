<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'reset_token','role', 'status'];
    protected $dates = ['deleted_at']; 
    /**
     * Define many-to-many relationship with Quiz.
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_assignments')->withTimestamps();
    }

    /**
     * Define one-to-many relationship with QuizAttempt.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // JWT-related methods

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
