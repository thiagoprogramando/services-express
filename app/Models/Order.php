<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'uuid',
        'user_id',
        'price_id',
        'template_id',
        'header',
        'footer',
        'value',
        'discount',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function price() {
        return $this->belongsTo(Price::class, 'price_id')->whereNull('deleted_at');
    }

    public function template() {
        return $this->belongsTo(Template::class, 'template_id')->whereNull('deleted_at');
    }


    public function labelStatus() {
        return $this->status == 1 ? 'Aprovado' : 'Pendente';
    }

}
