<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isAgent(): bool   { return $this->role === 'agent'; }
    public function isCustomer(): bool{ return $this->role === 'customer'; }

    public function tickets()        { return $this->hasMany(Ticket::class, 'customer_id'); }
    public function serviceWindows() { return $this->hasMany(ServiceWindow::class, 'agent_id'); }
    public function createdQueues()  { return $this->hasMany(Queue::class, 'created_by'); }
    public function notifications()  { return $this->hasMany(Notification::class, 'user_id'); }
    public function unreadNotifications() {
        return $this->notifications()->where('is_read', false);
    }
}
