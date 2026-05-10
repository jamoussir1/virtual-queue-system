<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceWindow extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'is_active', 'agent_id', 'queue_id'];

    public function agent()   { return $this->belongsTo(User::class, 'agent_id'); }
    public function queue()   { return $this->belongsTo(Queue::class); }
    public function tickets() { return $this->hasMany(Ticket::class, 'window_id'); }
}
