<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIData extends Model
{
    use HasFactory;

    protected $table = 'ai_data';

    protected $fillable = [
        'project_area',
        'project_type',
        'panel_name',
    ];
}
