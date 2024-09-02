<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Supervisor;

class UserController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index()
    {
        $users = User::where('role', '!=', 0)->get();

        return view('users', compact('users'));
    }

        
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
       
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        Excel::import(new UsersImport,request()->file('file'));
        return back();
    }

    public function listusers()
    {
        $users = User::where('role', '!=', 0)->get();
  
        return view('listusers', compact('users'));
    }

    public function edit(User $user)
    {
        return view('user.edit',compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'isPanel' => 'required',

        ]);
        
        $data = $request->all();

        
        $user->isPanel = $request->input('isPanel');
        
        
        // $user->fill($request->user())->save();
        $user->update($request->all());
        return redirect()->route('listusers')->with('success','User Has Been updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('listusers')->with('success','User has been deleted successfully');
    }


}
