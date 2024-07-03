<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Blog\Database\Factories\CategoryFactory;
use Modules\Clean\Casts\HtmlSpecialCharsCast;

class Category extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'cover_image_url',
        'category_id'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => HtmlSpecialCharsCast::class,
    ];


    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }


    /**
     * Category has children categories
     *
     * @return HasMany @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->with('categories');
    }


    /**
     * Posts belonging to categories
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'posts_categories');
    }


    /**
     * @param $query
     * @return mixed
     */
    public function scopeMostPopularCategories($query): mixed
    {
        return $query->withCount('posts')->latest('posts_count')->take(5)->get();
    }
}
