<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;


class AuthController extends Controller
{
    public function loginForm(){
        return view ('LoginPage');
    }

    public function loginCheck(Request $request){

        // Trim the email input so users who paste with whitespace still authenticate
        $request->merge([
            'email' => $request->has('email') ? trim((string) $request->input('email')) : null,
        ]);

        // Validate inputs so empty/invalid values produce validation errors
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials)) { // Auth::attempt returns boolean true or false. returns 1 for true.
            // Auth::attempt => authenticate a user based on provided credentials.
            // Then attempts to find a matching user in the database. 
            // If a user is found and the password matches, the user is logged in, and the method returns true. Otherwise, it returns false. 

            // $request->session()->regenerate();  // regenerate first

             $user = Auth::user();// this returns logedin user object, means user data.
          

            // 🔥 Role Based Redirect
        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role == 'teacher') {
            // store teacher_id and name AFTER regeneration or after login to session
            session(['teacher_id' => $user->teacher_id, 'teacher_name' => $user->name]);
            return redirect('/teacher/dashboard');
        }

        if ($user->role == 'student') {
            return redirect('/student/dashboard');
        }        
        }

        return back()->withErrors(['email' => 'Provided credential do not match'])->onlyInput('email'); // <--- THIS IS THE KEY PART;
        //return redirect()->route('students.index')->with('unsuccess', 'User Unable to Login.');        
        
        //return redirect('Dashboard',['user'=>$user]);
    }

    public function logout(Request $request){
        // Auth::guard('web')->logout();
        Auth::logout(); // logs out from default guard (web)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

        return redirect()->route('login.page');
    }
}