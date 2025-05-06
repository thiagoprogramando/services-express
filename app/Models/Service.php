<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model {

    use SoftDeletes;

    protected $table = 'services';  
    
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'value',
        'value_cost',
    ];

    public function fees() {
        return $this->hasMany(Fees::class, 'service_id', 'id');
    }

    public function priceServices(): HasMany {
        return $this->hasMany(PriceService::class);
    }

}
