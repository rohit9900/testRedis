<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fermion;

class FermionController extends Controller
{
    public static function arrFilter($key, $val)
    {
        $data = '';

        foreach($val as $k => $v)
                {
                    if(is_array($v))
                    {
                        $data .= self::arrFilter($k, $v);
                    }
                    else
                    {
                        $data .= 'Foo.'.$key.'.'.($k).': '.$v.PHP_EOL;
                    }
                }

        return $data;
    }

    public static function parentChild(array $tmpData, $parentID = 0)
    {
        $result = array();
        
        foreach($tmpData as $row)
        {
            if($row['parent_id'] == $parentID)
            {
                $child = self::parentChild($tmpData, $row['id']);
                
                if($child)
                {
                    $row['child'] = $child;
                }

                $result[] = $row;
            }
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tmpData    = Fermion::select('id','parent_id','menu_name')->orderBy('id')->get()->toArray();

        $data       = self::parentChild($tmpData, $parentID = 0);

        echo '<pre>';
        print_r($data);
        // if(!empty($data))
        // {
        //     return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Fermion Data', 'result'=> $data]);
        // }
        // else
        // {
        //     return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        // }
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
        $list = ['a string', ['a','b', ['c', 'd'] ], 'spam', ['eggs']];

        $str = '';
        $data = '';

        foreach($list as $key => $val)
        {
            if(is_array($val))
            {
                $data .= self::arrFilter($key, $val);
            }
            else
            {
                $data .= 'Foo.'.$key.': '.$val.PHP_EOL;
            }
        }

        print_r($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
