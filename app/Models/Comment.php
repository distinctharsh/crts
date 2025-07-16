<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = ['complaint_id', 'user_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
