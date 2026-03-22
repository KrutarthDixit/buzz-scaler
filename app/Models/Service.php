<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'type',
        'rate',
        'min',
        'max',
        'dripfeed',
        'refill',
        'cancel',
        'category',
    ];

    protected $casts = [
        'dripfeed' => 'boolean',
        'refill' => 'boolean',
        'cancel' => 'boolean',
    ];
}
