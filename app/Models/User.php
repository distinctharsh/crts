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
    public function getAssignableUsers($complaint = null)
    {
        $query = User::query()->with('role');

        // MANAGER or ADMIN
        if ($this->isAdmin() || $this->isManager()) {
            $query->whereHas('role', function ($q) {
                $q->whereIn('slug', ['vm', 'nfo']);
            });
            // 💡 Additional filter: same vertical only if complaint is given
            if ($complaint) {
                $query->whereHas('verticals', function ($q) use ($complaint) {
                    $q->where('vertical_id', $complaint->vertical_id);
                });
            }
        }
        // VM
        elseif ($this->isVM()) {
            $query->where(function ($q) use ($complaint) {
                // Only include self if not already assigned
                if (!$complaint || $complaint->assigned_to != $this->id) {
                    $q->where('id', $this->id);
                }
                $q->orWhereHas('role', function ($r) {
                    $r->where('slug', 'nfo');
                });
            });
            // 💡 Vertical match only if complaint is given
            if ($complaint) {
                $query->whereHas('verticals', function ($q) use ($complaint) {
                    $q->where('vertical_id', $complaint->vertical_id);
                });
            }
        }
        // NFO
        elseif ($this->isNFO()) {
            $query->whereHas('role', function ($q) {
                $q->where('slug', 'vm');
            });
            // 💡 Vertical match only if complaint is given
            if ($complaint) {
                $query->whereHas('verticals', function ($q) use ($complaint) {
                    $q->where('vertical_id', $complaint->vertical_id);
                });
            }
        }
        return $query->get(['id', 'username', 'full_name', 'role_id']);
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
