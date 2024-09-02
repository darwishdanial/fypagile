<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPSM2 extends Model
{
    use HasFactory;

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
        'password',
        'supervisorId'
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function panel()
    {
        return $this->belongsTo(User::class);
    }
}
