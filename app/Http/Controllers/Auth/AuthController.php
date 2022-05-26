<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;
use Mail;

use App\Models\Entity\User;
use App\Models\Entity\PasswordReset;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
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
            $user->assignRole('guest');

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
        $userData = User::where('id', auth()->user()->id)
            ->with(['roles' => function ($query) {
                $query->select('name', 'guard_name');
            }])
            ->first();

        $userData->role = $userData->getRoleNames()->first();

        return $this->sendSuccess($userData, 'berhasil mendapat profile', 200);
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
        $userData = User::where('id', auth()->user()->id)
            ->with(['roles' => function ($query) {
                $query->select('name', 'guard_name');
            }])
            ->first();

        $userData->role = $userData->getRoleNames()->first();

        $data = [
            'user' => $userData,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return $this->sendSuccess($data, $message, 200);
    }

    /**
     * It checks if the email exists in the database, if it does, it deletes the existing password
     * reset token and creates a new one, then sends an email to the user with the token
     *
     * @param ForgotPasswordRequest request The request object.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $input = $request->all();
        $token = Str::random(64);

        $user = User::where('email', $input['email'])->first();
        if(!$user){
            return $this->sendError(null, 'user tidak ditemukan', 404);
        }

        $existingReset = PasswordReset::where('email', $input['email'])->first();
        if($existingReset){
            $existingReset->delete();
        }

        $passwordReset = PasswordReset::create([
            'email' => $input['email'],
            'token' => $token
        ]);

        if($passwordReset){
            $data = [
                'email' => $input['email'],
                'token' => $token
            ];
            Mail::send('emails.forgot-password', $data, function($message) use ($input) {
                $message->to($input['email'])->subject('Reset Password');
            });
            return $this->sendSuccess(null, 'berhasil mengirim email', 200);
        } else {
            return $this->sendError(null, 'gagal mengirim permintaan reset password', 500);
        }
    }

    /**
     * The above function is used to reset the password of the user.
     *
     * @param ResetPasswordRequest request The request object.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $input = $request->all();

        $passwordReset = PasswordReset::where('token', $input['token'])->first();
        if(!$passwordReset){
            return $this->sendError(null, 'token tidak ditemukan', 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if(!$user){
            return $this->sendError(null, 'user tidak ditemukan', 404);
        }

        $user->password = Hash::make($input['password']);
        $changePassword = $user->save();

        $passwordReset->delete();

        if ($changePassword) {
            return $this->sendSuccess(null, 'berhasil reset password', 200);
        } else {
            return $this->sendError(null, 'gagal reset password', 500);
        }
    }
}
