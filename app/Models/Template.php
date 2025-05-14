<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model {
    
    use SoftDeletes;

    protected $table = 'templates';

    protected $fillable = [
        'user_id',
        'name',
        'header',
        'footer',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function labelStatus() {
        switch ($this->status) {
            case 0:
                return [
                    'status' => 'Indisponível',
                    'color' => 'danger',
                ];
            case 1:
                return [
                    'status' => 'Disponível',
                    'color' => 'success',
                ];
            default:
                return [
                    'status' => 'Disponível',
                    'color' => 'success',
                ];
        }
    }
}
