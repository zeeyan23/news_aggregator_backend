<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    protected $table = 'news_articles';
    protected $primaryKey = 'id';
    protected $fillable = ['source_name', 'author', 'title','description','url','url_to_image','published_at', 'content'];
    public $timestamps = false;

    public function readLater()
    {
        return $this->morphMany(ReadLaterModel::class, 'news');
    }
}
