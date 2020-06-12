<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
        event(new UserRegistered($user, $input['verifytoken']));
        return response()->json(['message' => 'registration succesfull'], 201);
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
     * function to get details of the user 
     * 
     * @return response
     */
    public function userDetails()
    {
        $user = User::with('')->find(Auth::user()->id);
        return response()->json([$user], 200);
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
}
