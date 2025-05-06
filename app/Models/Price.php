<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model {

    use SoftDeletes;

    protected $table = 'prices';
    
    protected $fillable = [
        'user_id',
        'client_id',
        'notes',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function services() {
        return $this->hasMany(PriceService::class, 'price_id');
    }
    
    public function fees() {
        return $this->hasMany(PriceServiceFee::class, 'price_id');
    }  

    public function labelStatus() {
        return $this->status == 1 ? 'Aprovado' : 'Pendente';
    }

    public function getTotalValueAttribute() {
        return $this->services->sum(function ($s) {
            $serviceValue = $s->value - $s->discount;
            $feesValue = $s->fees->sum(fn($f) => $f->value - $f->discount);
            return $serviceValue + $feesValue;
        });
    }

    public function getTotalServicesAttribute() {
        return $this->services->count();
    }

    public function getTotalFeesAttribute() {
        return $this->services->sum(fn($s) => $s->fees->count());
    }

}
