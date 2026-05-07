<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    protected $fillable = [
        'client_id',
        'nickname',
        'cep',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'reference',
        'instructions',
        'primary',
        'active'
    ];

    protected $casts = [
        'primary' => 'boolean',
        'active' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}