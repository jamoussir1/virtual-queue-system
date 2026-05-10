<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['qr_code', 'position', 'status', 'customer_id', 'queue_id', 'window_id', 'called_at', 'served_at'];

    protected $casts = [
        'called_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function queue()    { return $this->belongsTo(Queue::class); }
    public function window()   { return $this->belongsTo(ServiceWindow::class, 'window_id'); }
    public function notifications() { return $this->hasMany(Notification::class); }

    public static function generateQrCode(): string {
        return 'QR-' . date('Y') . '-' . strtoupper(Str::random(8));
    }

    public function getStatusBadgeClass(): string {
        return match($this->status) {
            'waiting'   => 'badge-warning',
            'called'    => 'badge-primary',
            'served'    => 'badge-success',
            'absent'    => 'badge-danger',
            'cancelled' => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }

    public function estimatedWait(): int {
        if ($this->status !== 'waiting') return 0;
        $ahead = Ticket::where('queue_id', $this->queue_id)
            ->where('status', 'waiting')
            ->where('position', '<', $this->position)
            ->count();
        return $ahead * 5;
    }
}
