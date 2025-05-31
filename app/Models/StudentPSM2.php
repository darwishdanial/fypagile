<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPSM2 extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;

    public $table = 'students_psm2';

    protected $fillable = [
        'course',
        'matric',
        'name',
        'title',
        'email',
        'phone',
        'cohort',
        'sessionpsm',
        'project_type',
        'project_area',
        'project_area_ai',
        'password',
        'supervisorId',
        'panelId',
        'panel2Id',
        'panelId_ai',
        'panel2Id_ai',
        'svReq',
        'sagile_link',
        'github_link',

    ];

    public function score(){

        return $this->hasMany(Score::class, 'student_psm2_id');
        
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function panel()
    {
        return $this->belongsTo(User::class);
    }
}
