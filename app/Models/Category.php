<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function getAllCategories(): Collection {
        return Category::all();
    }

    public function getCategoryById($categoryId) {
        return Category::find($categoryId);
    }

    public function addCategory($name) {
        return Category::create(['name' => $name]);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}

