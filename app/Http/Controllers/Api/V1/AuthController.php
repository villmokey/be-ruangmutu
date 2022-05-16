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
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors(), 400);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $success['token']['type']   =  'Bearer';
        $success['token']['token']  =  $user->createToken(env('APP_NAME'))->plainTextToken;
        $success['user'] =  $user;

        return $this->sendResponse($success, 'success register user');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token']['type'] =  'bearer';
            $success['token']['token'] =  $user->createToken(env('APP_NAME'))->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'success login user');
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
}
