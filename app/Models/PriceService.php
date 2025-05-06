<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceService extends Model {

    protected $table = 'price_services';

    protected $fillable = [
        'price_id', 
        'service_id', 
        'value',
        'discount',
        'quantity'
    ];

    public function price() {
        return $this->belongsTo(Price::class, 'price_id');
    }
    
    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }    

    public function fees() {
        return $this->hasMany(PriceServiceFee::class, 'price_service_id');
    }    
}
