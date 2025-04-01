<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PanelHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'panel_id',
        'project_area',
        'project_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'panel_id');
    }
}
