<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request){
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'country' => 'nullable|string|max:255',
            'interests' => 'nullable|string'
        ]);
        
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email already exists.'
            ], 409); // 409 Conflict status code
        }

        // Create a new user
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->country = $validatedData['country'] ?? null;
        $user->interests = $validatedData['interests'] ?? null;

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to authenticate the user with bcrypt hashed password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Return success response with user data
            return json_encode([
                'id' => $user->id,
                'name' => $user->name,
                'message' => 'Login successful'
            ]);
        } else {
            // If credentials are invalid, return error response
            return json_encode(['message' => 'Invalid email or password.'], 401);
        }
    }
}
