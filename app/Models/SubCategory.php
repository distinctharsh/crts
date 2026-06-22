<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'sub_categories';

    protected $fillable = [
        'vertical_id',
        'name',
        'short_form',
    ];

    public function vertical()
    {
        return $this->belongsTo(Vertical::class, 'vertical_id');
    }
}