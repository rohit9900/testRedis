<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CatController extends Controller
{

    public static function buildTree(array $elements, $parentId = 0) 
    {
        $result = array();    
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = self::buildTree($elements, $element['category_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $result[$element['category_id']] = $element;
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
        $categories   =     Category::select('category_id','parent_id','category_name')->orderBy('parent_id')->get()->toArray();

        $categoryData = self::buildTree($categories, $parentId = 0);

        if(!empty($categoryData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Category Data', 'result'=> $categoryData]);
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
            'parent_id'         =>'required|numeric',
            'category_name'     =>'required',
        ]);

        if($validator->passes())
        {
            $category                   =   new Category;

            $category->parent_id        =   $request->input('parent_id');
            $category->category_name    =   $request->input('category_name');
            $category->save();

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Added successfully', 'result'=> $category]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Invalid Data'.$validator->messages(), 'result'=> '']);      
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function show($category_id)
    {
        $categoryData   =   Category::where('category_id', $category_id)->first();

        if(!empty($categoryData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Category Data', 'result'=> $categoryData]);
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
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_id)
    {
        $categoryData   =   Category::where('category_id', $category_id)->first();

        if(!empty($categoryData))
        {
            $data       =   Category::find($category_id)->update($request->input());

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
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        $categoryData   =   Category::where('category_id', $category_id)->first();

        if(!empty($categoryData))
        {
            $data       =   Category::find($category_id);

            $data->delete();

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Deleted Successfully', 'result'=> '']);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }
}
