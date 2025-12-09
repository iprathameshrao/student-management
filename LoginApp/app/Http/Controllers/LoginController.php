<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class LoginController extends Controller
{
    public function loginCheck(Request $request){

        // Validate inputs so empty/invalid values produce validation errors
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email',$validated['email'])->first();
        if(Hash::check($validated['password'],$user->getAuthPassword())){
            return response()->json(['token'=>$user->createToken(time())->plainTextToken]);
        }
    }

    public function logout(Request $request)
{
    $request->user()->tokens()->delete(); // delete all access tokens
    //$request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out']);
}


}

