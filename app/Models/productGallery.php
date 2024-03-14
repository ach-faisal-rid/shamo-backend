<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class productGallery extends Model
{
    use HasFactory;

    protected $guarded = [
        'productID',
    ];

    // custom
    public function getUrlAttribute($url) {
        return config('app.url'). Storage::url($url);
    }
}
