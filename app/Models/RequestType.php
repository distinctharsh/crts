<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestType extends Model
{
    protected $fillable = ['name', 'slug'];

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'request_type_id');
    }
}
