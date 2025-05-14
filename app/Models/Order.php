<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'client_id',
        'price_id',
        'template_id',
        'status',
    ];

    protected $hidden = [
        'uuid',
    ];
}
