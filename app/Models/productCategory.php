<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productCategory extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // custom
    public function products() {
        return $this->hasMany(Product::class, 'categoriesID', 'id');
    }
}
