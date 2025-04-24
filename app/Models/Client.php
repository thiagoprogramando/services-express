<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {

    use SoftDeletes;
    
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'description',
        'cpfcnpj',
        'email',
        'phone',
        'postal_code',
        'address',
        'city',
        'province',
        'num',
        'notes',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function labelCpfCnpj(): string {

        $cpfcnpj = preg_replace('/\D/', '', $this->cpfcnpj);
        if (strlen($cpfcnpj) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfcnpj);
        } elseif (strlen($cpfcnpj) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cpfcnpj);
        }

        return $this->cpfcnpj;
    }

    public function labelPhone(): string {

        $phone = preg_replace('/\D/', '', $this->phone);
        if (preg_match('/^(\d{2})(9\d{4})(\d{4})$/', $phone, $matches)) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }

        if (preg_match('/^(\d{2})(\d{4})(\d{4})$/', $phone, $matches)) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }

        if (preg_match('/^(9\d{4})(\d{4})$/', $phone, $matches)) {
            return "{$matches[1]}-{$matches[2]}";
        }

        if (preg_match('/^(\d{4})(\d{4})$/', $phone, $matches)) {
            return "{$matches[1]}-{$matches[2]}";
        }

        return $this->phone;
    }

    public function address() {
        return $this->address . ', ' . $this->num . ' - ' . $this->postal_code . ', ' . $this->city . '/ ' . $this->province;
    }
}
