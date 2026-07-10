<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'slug'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'request_type_id');
    }
}
