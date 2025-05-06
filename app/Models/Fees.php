<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fees extends Model {
    
    use SoftDeletes;
    
    protected $table = 'service_fees';

    protected $fillable = [
        'service_id',
        'user_id',
        'name',
        'description',
        'value',
        'value_min',
        'value_max'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }

}
