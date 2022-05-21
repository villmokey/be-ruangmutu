<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Master\Document\CreateDocumentRequest;

use App\Http\Controllers\ApiController;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();

        try {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password'])
            ]);

            if ($user) {
                return $this->sendSuccess($user, null, 200);
            }

            return $this->sendError(null, null, 500);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    /**
     * login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();

        $user = User::where('email', $input['email'])->first();
        if(!$user){
            return $this->sendError(null, 'user tidak ditemukan', 404);
        }

        if(!Hash::check($input['password'], $user->password)){
            return $this->sendError(null, 'password tidak cocok', 400);
        }

        if (!$token = auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
            return $this->sendError(null, 'email atau password tidak cocok', 401);
        }

        return $this->respondWithToken($token, 'berhasil login');
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->sendSuccess(null, 'berhasil logout', 200);
    }

    /**
     * Refresh token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 'berhasil mendapatkan refresh token');
    }

    /**
     * Get user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return $this->sendSuccess(auth()->user(), 'berhasil mendapat profile', 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message)
    {
        $data = [
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return $this->sendSuccess($data, $message, 200);
    }
}
