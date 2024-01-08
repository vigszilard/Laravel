<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Amendment extends Model
{
    use HasFactory;

    protected $table = 'amendments';
    public $timestamps = false;

    protected $fillable = ['text', 'article_id'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function getAmendmentByArticleId($articleId)
    {
        return $this->where('article_id', $articleId)->first();
    }

    public function addAmendment($text, $articleId)
    {
        return $this->create([
            'text' => $text,
            'article_id' => $articleId,
        ]);
    }

    public function deleteAmendment($amendmentId)
    {
        return $this->where('id', $amendmentId)->delete();
    }
}
