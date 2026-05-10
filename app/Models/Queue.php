<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'max_capacity', 'created_by'];

    public function creator()        { return $this->belongsTo(User::class, 'created_by'); }
    public function tickets()        { return $this->hasMany(Ticket::class); }
    public function serviceWindows() { return $this->hasMany(ServiceWindow::class); }
    public function statistics()     { return $this->hasMany(Statistic::class); }

    public function waitingTickets() {
        return $this->tickets()->where('status', 'waiting')->orderBy('position');
    }

    public function nextPosition(): int {
        $last = $this->tickets()->max('position');
        return ($last ?? 0) + 1;
    }

    public function estimatedWaitMinutes(): int {
        $waiting = $this->waitingTickets()->count();
        return $waiting * 5; // 5 min avg per customer
    }
}
