<?php

namespace App\Models;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name'];

    /**
     * Get the product comments.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Filter instance of product model.
     *
     * @param $query
     * @param  QueryFilter  $filter
     * @return Builder
     */
    public function scopeFilter($query, QueryFilter $filter): Builder
    {
        return $filter->apply($query);
    }
}
