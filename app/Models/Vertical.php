<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Vertical extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['name'];

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
}
