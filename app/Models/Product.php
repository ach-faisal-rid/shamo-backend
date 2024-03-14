<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'categoriesID'
    ];

    // custom
    public function gallery() {
        return $this->hasMany(productGallery::class, 'productsID', 'id');
    }

    public function category() {
        return $this->belongsTo(productCategory::class, 'categoriesID', 'id');
    }
}
