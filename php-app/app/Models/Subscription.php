<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'service',
        'topic',
        'payload',
        'expired_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'expired_at' => 'datetime',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
