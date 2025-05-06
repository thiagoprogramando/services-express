<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceServiceFee extends Model {

    protected $table = 'price_service_fees';

    protected $fillable = [
        'price_id', 
        'price_service_id',
        'fee_id', 
        'value',
        'discount'
    ];

    public function priceService() {
        return $this->belongsTo(PriceService::class, 'price_service_id');
    }
    
    public function fee(): BelongsTo {
        return $this->belongsTo(Fees::class, 'fee_id');
    }
    
}
