<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::where('userId', $id)->first();

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => $user,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unable to retrieve users information',
   
        ], 400);
       
    }
}
