<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterOptionStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'filter_parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(FilterOptionStore::class, 'filter_parent_id');
    }

    public function children()
    {
        return $this->hasMany(FilterOptionStore::class, 'filter_parent_id');
    }
}
