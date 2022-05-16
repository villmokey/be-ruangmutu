<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;



class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors()->first(), 400);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $payload['token']['type']   =  'Bearer';
        $payload['token']['token']  =  $user->createToken(env('APP_NAME'))->plainTextToken;
        $payload['user'] =  $user;

        return $this->sendResponse($payload, 'success register user');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors()->first(), 400);
        }

        $user = User::where('email', $data['email'])->first();
        if(!$user){
            return $this->sendError('user not found', 'user not found', 400);
        }

        if(!Hash::check($data['password'], $user->password)){
            return $this->sendError('password not match', 'password not match', 400);
        }

        if (Auth::viaRemember()) {
            $payload = $this->loginPayload(auth()->user(), auth()->user()->tokens()->first()->token);

            return $this->sendResponse($payload, 'success login user');
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
            $user = Auth::user();
            $payload = $this->loginPayload($user, $user->createToken(env('APP_NAME'))->plainTextToken);

            return $this->sendResponse($payload, 'success login user');
        } else{
            return $this->sendError('unauthorized', ['error' => 'unauthorized'], 401);
        }
    }

    /**
     * Logout api
     *
     * @return A JSON object with a message.
     */
    public function logout()
    {
        if(auth()->user()->tokens()->delete()) {
            return $this->sendResponse([], 'success logout user');
        } else {
            return $this->sendError('failed logout user', ['error' => 'logout failed'], 400);
        }
    }

    /**
     * Profile api
     *
     * @return A JSON object with a message.
     */
    public function profile()
    {
        if(auth()->user()) {
            return $this->sendResponse(auth()->user(), 'success get profile');
        } else {
            return $this->sendError('failed get profile', ['error' => 'failed get profile'], 400);
        }
    }

    private function loginPayload($user, $token)
    {
        $payload['user'] =  $user;
        $payload['token']['type'] =  'bearer';
        $payload['token']['token'] =  $token;

        return $payload;
    }

}
