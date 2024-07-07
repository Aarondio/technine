<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrganisationController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $organisations = $user->organisations->each->makeHidden('pivot');
        return response()->json([
            'status' => 'success',
            'message' => 'Organisations retrieved successfully',
            'data' => [
                'organisations' => $organisations,
            ],
        ], 200);
    }

    public function show($orgId)
    {
        try{
            $organisation = Organisation::findOrFail($orgId);
            return response()->json([
                'status' => 'success',
                'message' => 'Organisation retrieved successfully',
                'data' => $organisation,
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Organisation not found ',
            ], 200);
        }
    
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $user = $request->user();
            $organisation = Organisation::create([
                'orgId' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $user->organisations()->attach($organisation->orgId);
            return response()->json([
                'status' => 'success',
                'message' => 'Organisation created successfully',
                'data' => $organisation,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Bearer token is missing or invalid',
            ], 401);
        }
    }

    public function addUser(Request $request, $orgId)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,userId',
        ], [
            'userId.exists' => 'User not found'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('userId', $request->userId)->first();
        $organisation = Organisation::where('orgId', $orgId)->first();

        if ($organisation) {
            if ($user) {
                $organisation->users()->attach($user->userId);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User added to organisation successfully',

                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to add User',

                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Organization could not be found',

            ], 400);
        }
    }
}
