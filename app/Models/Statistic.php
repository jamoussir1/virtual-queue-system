<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = ['period', 'total_served', 'avg_wait_time', 'queue_id'];

    protected $casts = ['period' => 'date'];

    public function queue() { return $this->belongsTo(Queue::class); }
}
