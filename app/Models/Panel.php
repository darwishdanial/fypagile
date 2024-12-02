<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        //'userId'
        'panel_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
