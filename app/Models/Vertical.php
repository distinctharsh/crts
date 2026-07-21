<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Vertical extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'short_form', 'parent_id', 'send_email'];

    public static function getNameById($id)
    {
        return static::find($id)->name ?? null;
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'vertical_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vertical');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function parent()
    {
        return $this->belongsTo(Vertical::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Vertical::class, 'parent_id')->with('children');
    }

    /**
     * Parent Chain IDs Array (Root to Last Child)
     * Output: [Root_ID, Sub_ID, Leaf_ID]
     */
    public function getAncestorIdsAttribute(): array
    {
        $ids = [$this->id];
        $curr = $this;
        while ($curr->parent) {
            array_unshift($ids, $curr->parent->id);
            $curr = $curr->parent;
        }
        return $ids;
    }

    /**
     * Parent Chain Names String (example: Network > Router > Port issue)
     */
    public function getFullPathAttribute(): string
    {
        $names = [$this->name];
        $curr = $this;
        while ($curr->parent) {
            array_unshift($names, $curr->parent->name);
            $curr = $curr->parent;
        }
        return implode(' - ', $names);
    }

    /**
     * Prefixes/Short Forms Combine
     */
    public function getCombinedPrefixAttribute(): string
    {
        $parts = [];
        $curr = $this;
        while ($curr) {
            if ($curr->short_form) {
                array_unshift($parts, strtoupper($curr->short_form));
            }
            $curr = $curr->parent;
        }
        return !empty($parts) ? implode('-', $parts) : 'CMP';
    }

    public function getDepthAttribute()
    {
        $depth = 0;
        $current = $this;
        while ($current->relationLoaded('parent') ? $current->parent : $current->parent()->first()) {
            $depth++;
            $current = $current->relationLoaded('parent') ? $current->parent : $current->parent()->first();
        }
        return $depth;
    }
}