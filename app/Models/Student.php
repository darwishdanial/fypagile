<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'course',
        'matric',
        'name',
        'email',
        'phone',
        'cohort',
        'sessionpsm',
        'psm',
        'password',
        'supervisorId'
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
