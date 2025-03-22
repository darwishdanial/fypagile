<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rubric extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'PSMType',
        'total_weight',
        'isEnable',
        'isSupervisorPSM1',
        'isPanelPSM1',
        'isSupervisorPSM2',
        'isPanelPSM2',
    ];

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function rubric()
    {
        return $this->hasMany(Score::class);
    }

}
