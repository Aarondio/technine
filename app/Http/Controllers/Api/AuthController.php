<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $formattedErrors = [];

            foreach ($errors->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $formattedErrors[] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }

            return response()->json([
                'errors' => $formattedErrors,
            ], 422);
        }

        $user = User::create([
            'userId' => Str::uuid(),
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $organisation = Organisation::create([
            'orgId' => Str::uuid(),
            'name' => $request->firstName . "'s Organisation",
            'description' => null,
        ]);

        $user->organisations()->attach($organisation->orgId);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => [
                'accessToken' => $token,
                'user' => $user,
            ],
        ], 201);
    }
    
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'token' => $token
            ]);
        } else {
            // Authentication failed
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }
    // public function login(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);
    
    //     if (Auth::attempt($validated)) {
    //         $user = Auth::user();
            
    //         // Set token expiration time (e.g., 7 days from now)
    //         $token = $user->createToken('auth_token', ['server:update'])
    //                       ->plainTextToken;
    
    //         $tokenInstance = $user->tokens->firstWhere('name', 'auth_token');
    //         $tokenInstance->expires_at = now()->addDays(7); // Adjust expiry time as needed
    //         $tokenInstance->save();
    
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Login successful',
    //             'data' => [
    //                 'accessToken' => $token,
    //                 'user' => $user,
    //             ],
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 'Bad request',
    //             'message' => 'Authentication failed',
    //             'statusCode' => 401,
    //         ], 401); 
    //     }
    // }
    
}
