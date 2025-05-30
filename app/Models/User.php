<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'photo',
        'name',
        'bio',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    public function maskedName() {
        if (!$this->name) {
            return '';
        }
    
        $nameParts = explode(' ', $this->name);
    
        $first = $nameParts[0] ?? '';
        $second = $nameParts[1] ?? '';
    
        return trim($first . ' ' . $second);
    }    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
