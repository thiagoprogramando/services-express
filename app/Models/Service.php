<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model {

    use SoftDeletes;

    protected $table = 'services';  
    
    protected $fillable = [
        'name',
        'description',
        'value',
        'value_cost',
    ];
}
