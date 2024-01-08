<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{

    use HasFactory;
    protected $table = 'articles';
    protected $fillable = ['title', 'content', 'author_id', 'category_id', 'is_approved'];
    public $timestamps = false;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Journalist::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getAllArticles(): Collection
    {
        return $this->all();
    }

    public function getApprovedArticles()
    {
        return $this->where('is_approved', 1)->get();
    }

    public function getDeclinedArticles()
    {
        return $this->where('is_approved', 0)->get();
    }

    public function getArticleById($articleId)
    {
        return $this->find($articleId);
    }

    public function addArticle($title, $content, $authorId, $categoryId)
    {
        return $this->create([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'category_id' => $categoryId,
        ]);
    }

    public function updateArticle($articleId, $title, $content, $authorId, $categoryId, $isApproved)
    {
        return $this->where('id', $articleId)->update([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'category_id' => $categoryId,
            'is_approved' => $isApproved,
        ]);
    }

    public function getArticlesByCategoryId($categoryId)
    {
        return $this->where('category_id', $categoryId)->get();
    }

    public function getArticlesByAuthorId($authorId)
    {
        return $this->where('author_id', $authorId)->get();
    }

    public function deleteArticle($articleId)
    {
        return $this->where('id', $articleId)->delete();
    }
}

