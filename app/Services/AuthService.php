<?php

namespace App\Services;


use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Panel;
use App\Models\Supervisor;
use App\Models\Coordinator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Services\StudentService;
use App\Services\SupervisorService;

class AuthService
{
    protected $studentService;
    protected $supervisorService;

    public function __construct(StudentService $studentService, SupervisorService $supervisorService)
    {
        $this->studentService = $studentService;
        $this->supervisorService = $supervisorService;
    }
    public function validate_registration($data)
    {
        
        $user = User::create([
            'name'  =>  $data['name'],
            'username' =>  $data['username'],
            'email' =>  $data['email'],
            // 'password' => Hash::make($data['password']),
            'password' => Hash::make('123456'),
            'role'=> $data['role'],
            'isPanel'=> $data['panel']
            // 'password' => Hash::make(Str::random(10))
            
        ]);

        // Admin=0 , Coordinator=1, Supervisor=2
        if($data['role']==1){
            Coordinator::create([
            'userId'  =>  $user->id,
            
            ]);
            Supervisor::create([
                'userId'  =>  $user->id,
                
            ]);
        }

        if($data['role']==2){
            Supervisor::create([
            'userId'  =>  $user->id,
            
            ]);
        }

        if($data['panel']==1){
            Panel::create([
            'userId'  =>  $user->id,
            ]);
        }
        
    }

    public function validate_login($request){

        $credentials = $request->only('username', 'password');

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $role_name = 'null';
            switch ($user->role) {
                case 0:
                    $role_name = 'Admin';
                    break;
                case 1:
                    $role_name = 'Coordinator';
                    break;
                case 2:
                    $role_name = 'Supervisor';
                    break;
                // case 3: 
                //     $role_name = 'Panel';
                //     break;
                default : 'null';
            }

            // TODO session set login user
            $request->session()->put('role_id', $user->role);
            $request->session()->put('name', $user->name);
            $request->session()->put('role_name', $role_name);
            $request->session()->put('id', $user->id);
            $request->session()->put('panel', $user->isPanel);
        }
    }

    public function dashboard(){

        if(Auth::check()){   

            $student=0;
            $student2=0;
            $supervisor=0;
            $userCounts=0;
            
            if(Session::get('role_id')==0){

                $users = User::where('role', '!=', 0)->get();

                $userCounts = [
                    'totalUsers' => count($users),
                    'coordinators' => 0,
                    'supervisors' => 0
                ];
            
                foreach ($users as $user) {
                    if ($user->role == 1) {
                        $userCounts['coordinators']++;
                    } elseif ($user->role == 2) {
                        $userCounts['supervisors']++;
                    }
                }
            }

            if(Session::get('role_id')==1){
                $student = $this->studentService->getStudents("PSM1")->count();
                $student2 =$this->studentService->getStudents("PSM2")->count();
                $supervisor = $this->supervisorService->totalSupervisor();
            } 
            if(Session::get('role_id')==2){
                // $student = $this->studentService->getStudentSupervisor()->count();
                // $student2 = $this->studentService->getStudentSupervisor()->count();

            
                // $supervisor = (new SupervisorController)->totalSupervisor();
            }

            // $student = (new StudentController)->totalStudent();
            // $supervisor = (new SupervisorController)->totalSupervisor();
            $data = compact('student','student2','supervisor','userCounts');
            return $data;
        }
    }

    public function updatePassword($old_password, $new_password)
    {
        #Match The Old Password
        if(!Hash::check($old_password, auth::user()->password)){
            throw ValidationException::withMessages([
                'old_password' => ["Old Password doesn't match!"]
            ]);
        }

        #Update the new Password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($new_password)
        ]);
    }
}