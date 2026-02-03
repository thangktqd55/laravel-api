<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = [
        'post_id',
        'meta_title',
        'mete_description',
        'meta_keywords',
    ];
}
