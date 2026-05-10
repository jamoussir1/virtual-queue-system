<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'message', 'is_read', 'ticket_id', 'user_id', 'sent_at'];

    protected $casts = [
        'sent_at'  => 'datetime',
        'is_read'  => 'boolean',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function ticket() { return $this->belongsTo(Ticket::class); }
}
