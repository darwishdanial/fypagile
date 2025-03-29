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
        'student_psm1_id',
        'student_psm2_id',
        'panel_id',
        'panel_name'
    ];

    public function rubric()
    {
        return $this->belongsTo(Rubric::class)->withTrashed();
    }

    public function student_psm1()
    {
        return $this->belongsTo(StudentPSM1::class, 'student_psm1_id');
    }

    public function student_psm2()
    {
        return $this->belongsTo(StudentPSM2::class, 'student_psm2_id');
    }

    public function panel()
    {
        return $this->belongsTo(User::class,'panel_id');
    }
}
