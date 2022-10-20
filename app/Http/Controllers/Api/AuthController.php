<?php
namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController
{
    protected $authService;
    private $validator;

    public function __construct(
        AuthService $authService,
        AuthValidator $validator
    ) {
        $this->authService = $authService;
        $this->validator   = $validator;
    }

    public function register(Request $request)
    {
        $this->validator->isValid($request, 'REGISTER');
        $user = $this->authService->register($request);

        return response()->json(['data' => $user], 200);
    }

    public function login(Request $request)
    {
        $this->validator->isValid($request, 'LOGIN');
        $token = $this->authService->login($request);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ], 200);
    }

    public function me(Request $request)
    {
        $user = auth()->user();
        Log::info('user: ' . json_encode($user));
        if (empty($user)) {
            return response()->json(['error' => 'Không tồn tại người dùng'], 500);
        }

        return response()->json(['data' => $user], 200);
    }
}
