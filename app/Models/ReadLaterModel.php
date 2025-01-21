<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadLaterModel extends Model
{
    protected $table = 'read_later';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'news_id', 'news_type'];

    public function news()
    {
        // Dynamically return the correct news model based on the news_type
        return $this->morphTo(null, 'news_type', 'news_id');
    }
}
