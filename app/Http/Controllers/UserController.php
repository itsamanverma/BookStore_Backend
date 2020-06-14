<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\UserRegistered;


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
            'dob' => 'date_format:Y-m-d|required|before:today|nullable',
            'region' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 210);
        }

        $input["created_at"] = now();
        $input['password'] = bcrypt($input['password']);
        $input['verifytoken'] =Str::random(60);
        $date = str_replace("-", "", $request->dob);
        $input['dob'] = Carbon::parse($date)->format('Y-m-d');
        $user = User::create($input);
        $success['token'] = $user->createToken('bookstore')->accessToken;
        $success['firstname'] = $user->firstname;
        // event(new UserRegistered($user, $input['verifytoken']));
        return response()->json(['message' => 'registration succesfull','success' => $success], 201);
     }

     /**
     * function to login user 
     * 
     * @return Illuminate\Http\Response
     */
    public function login()
    {
        //getting user email
        $email = request('email');
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if ($user->email_verified_at === null) {
                return response()->json(['message' => 'Email Not Verified'], 211);
            }
            $token = $user->createToken('bookstore')->accessToken;
            return response()->json(['token' => $token, 'userdetails' => Auth::user()], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 204);
        }
    }

    /**
     * function to logout user 
     * 
     * @return Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->token()->revoke();
        
        return response()->json(['message'=>'Logout SuccesFull'],200);
    }

    /**
     * function to help forgot password of the user 
     * 
     * @return response
     */
    public function forgotPassword()
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }
    }

    /**
     * function to verify email of the user and add the time stamp to user verified field in user table
     * 
     * @return Illuminate\Http\Response
     */
    public function verifyEmail()
    {
        $id = request('id');
        $token = request('token');
        $user = User::where("verifytoken", $token)->first();
    //    / $user = User::where("email", $email)->first();
        if (!$user) {
            return response()->json(['message' => "Not a Registered Email"], 200);
        } else if ($user->email_verified_at === null) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['message' => "Email Successfully Verified"], 201);
        } else {
            return response()->json(['message' => "Email Already Verified"], 202);
        }
    }
}
