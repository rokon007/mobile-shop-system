<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's sales records.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'created_by');
    }

    /**
     * Get the user's purchase records.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'created_by');
    }

    /**
     * Get the user's expense records.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    /**
     * Get the user's employee record.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get user's primary role name.
     */
    public function getPrimaryRole()
    {
        return $this->getRoleNames()->first() ?? 'User';
    }

    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrl()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('assets/img/profile-30.png');
    }
}
