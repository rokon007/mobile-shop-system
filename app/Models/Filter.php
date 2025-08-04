<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'is_active',];

    public function options()
    {
        return $this->hasMany(FilterOption::class);
    }
}
