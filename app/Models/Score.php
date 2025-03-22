<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Score extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'rubric_id',
        'student_id',
        'mark',
        'comment',
        
    ];

    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }
}
