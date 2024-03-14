<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $guarded = [
        'usersID',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'usersID', 'id');
    }

    public function items() {
        return $this->hasMany(transactionItem::class, 'transactionID', 'id');
    }
}
