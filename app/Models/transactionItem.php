<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionItem extends Model
{
    use HasFactory;

    protected $guarded = [
        'usersID',
        'productID',
        'transactionID'
    ];

    // custom
    public function product() {
        return $this->hasOne(Product::class, 'id', 'productID');
    }
}
