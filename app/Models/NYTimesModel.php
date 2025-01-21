<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NYTimesModel extends Model
{
    public $timestamps = false;
    protected $table = 'nytimes_news';
    protected $primaryKey = 'id';
    protected $fillable = ['section', 'subsection', 'title', 'abstract', 'url',
        'uri', 'byline', 'item_type', 'published_date', 'imageUrl'];

        public function readLater()
        {
            return $this->morphMany(ReadLaterModel::class, 'news');
        }
}
