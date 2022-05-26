<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        // 1
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        // 2    

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        // 3    

        $token = $user->createToken($request->email)->plainTextToken;
        // 4    

        return response()->json(['token' => $token], 200);
    }
    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        // 1

        $user = User::where('email', $request->email)->first();
        // 2
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }
        // 3

        return response()->json(['token' => $user->createToken($request->email)->plainTextToken]);
        // 4
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            return response(200);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
