<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\TestRedis;


class TestRedisController extends Controller
{

    public function index()
    {
        $allKeys    =   Redis::keys('*');
        $response   =   array();

        foreach($allKeys as $key => $val)
        {
            if(str_contains($val, 'laravel_did_log'))
            {
                $response[] = json_decode(Redis::get($val), true);
            }
        }

        return json_encode($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dids.create');
    }

    public function store( Request $request )
    {
        $did_number =   $request->input('did_number');
        $active     =   $request->input('active');
        $did_id     =   Redis::incr('1');

        Redis::set('laravel_did_log:did_id:'. $did_id, json_encode([
                'did_id' => $did_id, 
                'did_number' => $did_number, 
                'active' => $active
            ])
        );

    }

    public function update(  Request $request , $did_id )
    {
        $checkExist     =   Redis::get('laravel_did_log:did_id:'. $did_id );
        $tmp            =   json_decode($checkExist);

        if($checkExist)
        {
            $did_number =   empty($request->input('did_number'))        ?  $tmp->did_number   :   $request->input('did_number');
            $active     =   empty(strlen($request->input('active')))    ?  $tmp->active       :   $request->input('active');

            $response   =   Redis::set('laravel_did_log:did_id:'. $did_id , json_encode([
                                'did_id' => $did_id, 
                                'did_number' => $did_number,
                                'active' => $active
                            ]));
            if($response)
            {
                $result     =   Redis::get('laravel_did_log:did_id:'. $did_id );
            }
        }

        return $result;
    }

    public function show( $did_id )
    {
        $response = Redis::get('laravel_did_log:did_id:'. $did_id);

        return $response;
    }

    
}
