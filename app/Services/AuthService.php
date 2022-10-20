<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        if (!$this->repository->findByField('email', $request->get('email'))->isEmpty()) {
            return response()->json(['error' => 'Email đã tồn tại'], 500);
        }
        $data           = $request->all();
        $user           = $this->repository->create($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $user = $this->repository->findByField('email', $credentials['email'])->first();
            if (!$user) {
                return response()->json(['error' => 'Email đã tồn tại'], 500);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json(['error' => 'Mật khẩu không đúng'], 500);
            }

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Không tạo được token'], 500);
            }

            return $token;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Không tạo được token'], 500);
        }
    }
}
