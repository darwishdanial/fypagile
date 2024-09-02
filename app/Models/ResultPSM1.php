<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultPSM1 extends Model
{
    use HasFactory;

    public $table = 'result_psm1';

    protected $fillable = [
        'studentId',
        'svId',
        'panelId',
        'proposal',
        'planning',
        'clo1',
        'clo2',
        'i2clo1',
        'i2clo2',
        'i3clo1',
        'i3clo2',
        'i4clo1',
        'i4clo2',
        'report',
        'presentation',
        'application',
    ];
}
