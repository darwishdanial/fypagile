<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Panel;
use App\Models\Supervisor;
use App\Models\Coordinator;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Http\Request;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorController;
use Hash;
use Session;

class AuthController extends Controller
{
    function index(){
        return view('login');
    }

    function registration()
    {
        return view('registration');
    }

    function validate_registration(Request $request)
    {
        $request->validate([
            'name'         =>   'required',
            'username'     =>   'required',
            'email'        =>   'required|email|unique:users',
            // 'password'     =>   'required|min:6',
            'role'         =>   'required',
            'panel'         =>  'required'
        ]);

        $data = $request->all();
        
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
        
        return redirect('registration')->with('success', 'Registration Completed');
    }

    function validate_login(Request $request)
    {
        $request->validate([
            'username' =>  'required',
            'password'  =>  'required'
        ]);

        $credentials = $request->only('username', 'password');

        if(Auth::attempt($credentials))
        {
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
            }

            // TODO session set login user
            $request->session()->put('role_id', $user->role);
            $request->session()->put('name', $user->name);
            $request->session()->put('role_name', $role_name);
            $request->session()->put('id', $user->id);
            $request->session()->put('panel', $user->isPanel);
            return redirect('dashboard');
        }

        return redirect('login')->with('error', 'Login details are not valid');
    }

    function dashboard()
    {
        if(Auth::check())
        {   
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
                $student = (new StudentController)->totalStudent();
                $student2 = (new StudentController)->totalStudent2();
                $supervisor = (new SupervisorController)->totalSupervisor();
            } 
            if(Session::get('role_id')==2){
                $student = (new StudentController)->getStudentSupervisor()->count();
                $student2 = (new StudentController)->getStudentSupervisor2()->count();

            
                // $supervisor = (new SupervisorController)->totalSupervisor();
            }

            // $student = (new StudentController)->totalStudent();
            // $supervisor = (new SupervisorController)->totalSupervisor();
            $data = compact('student','student2','supervisor','userCounts');
            return view('dashboard',$data);
        }

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    public function changePassword()
    {
    return view('change-password');
    }

    public function updatePassword(Request $request)
    {
            # Validation
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);


            #Match The Old Password
            if(!Hash::check($request->old_password, auth()->user()->password)){
                return back()->with("error", "Old Password Doesn't match!");
            }


            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with("status", "Password changed successfully!");
    }

    function logout()
    {
        Session::flush();

        Auth::logout();

        return Redirect('login');
    }
}


