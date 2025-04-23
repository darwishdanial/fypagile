<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\StudentService;
use App\Services\SupervisorService;
use App\Services\AuthService;

class AuthController extends Controller
{

    protected $studentService;
    protected $supervisorService;
    protected $authService;

    public function __construct(StudentService $studentService, SupervisorService $supervisorService, AuthService $authService){

        $this->studentService = $studentService;
        $this->supervisorService = $supervisorService;
        $this->authService = $authService;
    }
    public function index(){

        return view('login');
    }

    public function registration()
    {
        return view('registration');
    }

    public function validate_registration(Request $request)
    {
        $request->validate([
            'name'         =>   'required',
            'username'     =>   'required',
            'email'        =>   'required|email|unique:users',
            // 'password'     =>   'required|min:6',
            'role'         =>   'required',
            'panel'         =>  'required'
        ]);

        try{
            $this->authService->validate_registration($request->all());
            return redirect('registration')->with('success', 'Registration Completed');
        }catch(\Exception $e){
            return redirect('registration')->with('error', 'Registration Failed');
        }

    }

    public function validate_login(Request $request)
    {
        $request->validate([
            'username' =>  'required',
            'password'  =>  'required'
        ]);

        try{
            $this->authService->validate_login($request);
            return redirect('dashboard');
        }catch(\Exception $e){
            return redirect()->route('login');
        }

    }

    public function dashboard()
    {
        try{

            $user = Auth::user();

            if ($user->role == 1) {
                return redirect()->route('coordinator.home')->with('success', 'Logged in as Coordinator');
            } elseif ($user->role == 2) {
                return redirect()->route('panel.home')->with('success', 'Logged in as Panel');
            }

        }catch(\Exception $e){

            logger($e->getMessage());

            return redirect()->route('login')->with('error', 'Invalid user');
        }

    }

    public function changePassword()
    {
        return view('change-password');
    }

    public function updatePassword(Request $request)
    {
        try{
            $this->authService->updatePassword($request->old_password, $request->new_password);
            return back()->with("status", "Password changed successfully!");
        }catch(\Exception $e){
            return back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('login');
    }
}


