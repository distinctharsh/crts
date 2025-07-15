<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Status extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'name', 'color', 'slug', 'visible_to_user', 'sort_order', 'description'
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Relationships
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Helper methods
    public function getDisplayNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->name));
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
