<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Complaint;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use LogsActivity, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone_number',
        'password',
        'full_name',
        'address',
        'role_id',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'must_change_password' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'client_id');
    }

    public function assignedComplaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function actions()
    {
        return $this->hasMany(Tms::class, 'action_by');
    }

    public function previousAssignments()
    {
        return $this->hasMany(Tms::class, 'previous_assigned_to');
    }

    public function newAssignments()
    {
        return $this->hasMany(Tms::class, 'new_assigned_to');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    /**
     * Check if user is a manager
     */
    public function isManager(): bool
    {
        return $this->role && $this->role->slug === 'manager';
    }

    /**
     * Check if user is a VM
     */
    public function isVM(): bool
    {
        return $this->role && $this->role->slug === 'vm';
    }

    /**
     * Check if user is an NFO
     */
    public function isNFO(): bool
    {
        return $this->role && $this->role->slug === 'nfo';
    }

    /**
     * Check if user is a regular user
     */
    public function isRegularUser(): bool
    {
        return $this->role && $this->role->slug === 'client';
    }

    /**
     * Get all complaints for the user based on their role
     */
    public function getComplaints()
    {
        if ($this->isAdmin() || $this->isManager()) {
            $activeStatusIds = Status::whereIn('name', ['pending', 'assigned', 'in_progress'])->pluck('id');
            return Complaint::whereIn('status_id', $activeStatusIds)->get();
        }

        if ($this->isVM()) {
            return Complaint::all();
        }

        if ($this->isNFO()) {
            return Complaint::where('assigned_to', $this->id)->get();
        }

        return Complaint::where('client_id', $this->id)->get();
    }

    /**
     * Get users that can be assigned to complaints based on current user's role
     */
    public function getAssignableUsers($complaint = null, $verticalId = null)
    {
        return User::query()
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.id', 'users.username', 'users.full_name', 'users.role_id')
            ->orderByRaw("
                CASE 
                    WHEN LOWER(roles.slug) = 'manager' THEN 1
                    WHEN LOWER(roles.slug) = 'vm' THEN 2
                    WHEN LOWER(roles.slug) = 'nfo' THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderBy('users.full_name', 'asc')
            ->get();
    }
    

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function verticals()
    {
        return $this->belongsToMany(Vertical::class, 'user_vertical');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->useLogName('user')
            ->logOnlyDirty();
    }
}
