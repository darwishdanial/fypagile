<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubric extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'PSMType',
        'total_weight',
        'session',
        'progress',
        'isEnable',
        'roleType',
        'rubricType',
    ];

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function rubric()
    {
        return $this->hasMany(Score::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

}
