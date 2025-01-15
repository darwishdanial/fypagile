<?php

namespace App\Services;

use App\Models\Supervisor;
use App\Models\Student;
use App\Models\ResultPSM1;
use App\Models\ResultPSM2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Services\StudentService;


class SupervisorService {

    public function totalSupervisor(){
        
        return Supervisor::count();;
    }
}