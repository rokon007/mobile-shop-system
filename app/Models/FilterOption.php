<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterOption extends Model
{
    use HasFactory;
    protected $fillable = ['filter_id', 'value', 'is_active',];

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }
}
