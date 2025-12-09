<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return $users;
    }
    // Show registration form
    public function create()
    {
        return view('CreateUser'); // use kebab or snake view name
    }

    // Handle form submit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // use table name 'users' or Rule::unique(User::class)
            'email' => ['required', 'string', 'email', 'max:255'],//, 'unique:users,email'
            'password' => ['required'],
            'role'=> ['required']
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        // HASH the password before saving
        $user->password = Hash::make($validated['password']);//Hash::make($validated['password'])
        $user->role = $validated['role'];
        $user->save();

        // redirect back or to some page with success message
        return redirect()->route('login.page')->with('success', 'User created successfully.');
        // OR: return response()->json(['message' => 'User created successfully.']);
    }
    
    // delete user from database.
    public function destroy($id){
        $user = User::find($id);
      if ($user) {
        $user->delete();
        return redirect()->route('students.index')->with('status','Student deleted successfully');
    }

    return redirect()->route('students.index')->with('error','Student not found');
}

}