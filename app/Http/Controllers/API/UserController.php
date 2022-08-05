<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userData   =   User::all();

        if(!empty($userData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'User Data', 'result'=> $userData]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation         
        $validator = Validator::make($request->all(),[
                'first_name'   =>'required',
                'last_name'    =>'required',
                'password'     =>'required',
                'email'        =>'required|email',
        ]);

        if($validator->passes())
        {
            $user               =   new User;

            $user->first_name   =   $request->input('first_name');
            $user->last_name    =   $request->input('last_name');
            $user->password     =   Hash::make($request->input('password'));
            $user->email        =   $request->input('email');
            $user->save();

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Added successfully', 'result'=> $user]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Invalid Data'.$validator->messages(), 'result'=> '']);      
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userData   =   User::where('id', $id)->first();

        if(!empty($userData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'User Data', 'result'=> $userData]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userData   =   User::where('id', $id)->first();

        if(!empty($userData))
        {
            $data   =   User::find($id)->update($request->input());

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Updated Successfully', 'result'=> $data]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userData   =   User::where('id', $id)->first();

        if(!empty($userData))
        {
            $user   =   User::find($id);

            $user->delete();

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Deleted Successfully', 'result'=> '']);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }
}
