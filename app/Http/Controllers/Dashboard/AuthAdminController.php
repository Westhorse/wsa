<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Dashboard\AdminResource;
use App\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(AdminRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function login(Request $request , Admin $admin): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $credentials['email'])->first();
        activity()->performedOn($admin)->withProperties(['attributes' => $admin])->log('login');
        if ($admin && Hash::check($credentials['password'], $admin->password)) {

            $token = $admin->createToken('admin-token')->plainTextToken;
            return response()->json([
                'data' => new AdminResource($admin),
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'result' => 'Error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout()
    {
        try {
            auth('admins')->user()->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }
    public function getCurrentAdmin()
    {
        try {
            $admin = auth('admins')->user();
            return response()->json([
                'data' =>  new AdminResource($admin)
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }

}
