<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * function to register user
     * 
     *@param Request $request
     *@return Illuminate\Http\Response 
     */
     public function register(Request $request){

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'email' => 'bail|required|email|unique:users',
            'password' => 'required|min:8|max:15',
            'dob' => 'required|date_format:Y-M-D|before:today',
            'region' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 210);
        }

        $input["created_at"] = now();
        $input['password'] = bcrypt($input['password']);
        $input['verifytoken'] = str_random(60);
        $user = User::create($input);
        $success['token'] = $user->createToken('fundoo')->accessToken;
        $success['firstname'] = $user->firstname;
        // event(new UserRegistered($user, $input['verifytoken']));
        return response()->json(['message' => 'registration succesfull'], 201);
     }
}