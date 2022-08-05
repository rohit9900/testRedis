<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function login(Request $request)
   {
        $user   =    User::where('email', request('email'))->first();

        if($user)
        {
            if($user->remember_token == '')
            {
                if(Hash::check(request('password'),$user->password))
                {
                    //Create Token Here
                    $token                  =   $user->createToken('MyApp')->accessToken;
                    $user->remember_token   =   $token;

                    $user->save();

                    return response()->json(['status'=>'success', 'message'=>'Login Successfull', 'user'=>$user, 'token'=>$token], 200);
                }
                else
                {
                    return response()->json(['status'=>'error', 'message'=>'Invalid Password'], 400);
                }
            }
            else
            {
                return response()->json(['status'=>'error', 'message'=>'User already Login', 'result' => $user], 200);
            }
        }
        else
        {
            return response()->json(['status'=>'error', 'message'=>'Invalid Credentials'], 400);
        }
   }

   //Logout User
   public function logout(Request $request)
   {     
        $user   =    User::where('id', request('id'))->first();

        if($user)
        {
            if($user->remember_token != '')
            {
                $user->remember_token = '';
                $user->save();

                return response()->json(['status'=>'success', 'message'=>'User Logout successfully', 'token'=>''], 200);
            }
            else
            {
                return response()->json(['status'=>false, 'message'=>'User Already Logout', 'token'=>null], 200);
            }
        }
        else
        {
                return response()->json(['status' => 'error','message' => 'User Not Found','token'=>null], 400);
        }
    }
}
