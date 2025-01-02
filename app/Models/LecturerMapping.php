<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerMapping extends Model
{
    use HasFactory;

    protected $table = 'lecturer_mapping';

    protected $fillable = [
        'name',
        'number',
    ];
}
