<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class NetworkType extends Model
{
    use LogsActivity, SoftDeletes;
    //

    protected $fillable = [
        'name',
    ];

    public static function getNameById($id)
    {
        return static::find($id)->name ?? null;
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'network_type_id');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults();
    }
}
