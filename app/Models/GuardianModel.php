<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianModel extends Model
{
    protected $table = 'guardian_news';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'sectionId', 'sectionName','webPublicationDate','webTitle','webUrl','apiUrl', 'isHosted','pillarId','pillarName'];

    public function readLater()
    {
        return $this->morphMany(ReadLaterModel::class, 'news');
    }
}
