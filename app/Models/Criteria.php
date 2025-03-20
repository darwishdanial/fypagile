<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criteria extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'rubric_id',
        'weight',
    ];

    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }
}
