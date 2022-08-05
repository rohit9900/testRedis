<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_Category_Master;
use DB;

class ProdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \DB::statement("SET SQL_MODE=''");
        
        $productData   =   DB::table('products')->select('products.product_name','products.product_price', DB::raw('group_concat(categories.category_name) as category_name'))
                                ->join('product_category_master', 'product_category_master.product_id', '=', 'products.product_id')
                                ->join('categories', 'categories.category_id', '=', 'product_category_master.category_id')
                                ->groupBy('product_category_master.product_id')
                                ->get();

        if(!empty($productData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Product Data', 'result'=> $productData]);
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
            'product_name'      =>'required',
            'category_id'       =>'required',
            'product_price'     =>'required|numeric',
        ]);

        if($validator->passes())
        {
            $category_id    =   $request->input('category_id');

            $product                    =   new Product;
            $product->product_name      =   $request->input('product_name');
            $product->product_price     =   $request->input('product_price');
            $product->save();

            $tmp = Product_Category_Master::where('product_id', $product->product_id)->get();

            if(count($tmp) > 0)
            {
                foreach($tmp as $row)
                {
                    //DELETE ALL DATA
                    $rslt = Product_Category_Master::find($row->id);
                    $rslt->delete();
                }
            }

            //INSERT PRODUCT CATEGORIES
            foreach($category_id as $key => $val )
            {
                $tmpData = Product_Category_Master::where('product_id', $product->product_id)
                                                  ->where('category_id', $val)
                                                  ->first();
                
                if(empty($tmpData))
                {
                    $ProdCatMaster              =   new Product_Category_Master;
                    $ProdCatMaster->product_id  =   $product->product_id;
                    $ProdCatMaster->category_id =   $val; 
                    $ProdCatMaster->save();
                }
                
            }

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Added successfully', 'result'=> $product]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Invalid Data'.$validator->messages(), 'result'=> '']);      
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        \DB::statement("SET SQL_MODE=''");
        
        $productData   =   DB::table('products')->select('products.product_name','products.product_price', DB::raw('group_concat(categories.category_name) as category_name'))
                                ->join('product_category_master', 'product_category_master.product_id', '=', 'products.product_id')
                                ->join('categories', 'categories.category_id', '=', 'product_category_master.category_id')
                                ->where('products.product_id', $product_id)
                                ->first();

        if(!empty($productData))
        {
            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Product Data', 'result'=> $productData]);
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
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_id)
    {
        $productData    =   Product::where('product_id', $product_id)->first();

        if(!empty($productData))
        {
            //update product table
            $product       =   Product::find($product_id)->update($request->input());

            //update product_category_master table
            $category_id    =   $request->input('category_id');

            if(!empty($category_id))
            {
                $tmp = Product_Category_Master::where('product_id', $product_id)->get();

                if(count($tmp) > 0)
                {
                    foreach($tmp as $row)
                    {
                        //DELETE ALL DATA
                        $tmpData = Product_Category_Master::find($row->id);
                        $tmpData->delete();
                    }
                }
                
                //INSERT PRODUCT CATEGORIES
                foreach($category_id as $key => $val )
                {
                    $tmpData = Product_Category_Master::where('product_id', $product_id)
                                                    ->where('category_id', $val)
                                                    ->first();
                    
                    if(empty($tmpData))
                    {
                        $ProdCatMaster              =   new Product_Category_Master;
                        $ProdCatMaster->product_id  =   $product_id;
                        $ProdCatMaster->category_id =   $val; 
                        $ProdCatMaster->save();
                    }
                    
                }
            }

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Updated Successfully', 'result'=> $product]);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        $productData    =   Product::where('product_id', $product_id)->first();

        if(!empty($productData))
        {
            $data       =   Product::find($product_id);

            $data->delete();

            return response()->json(['status'=>'success', 'code'=> 200, 'message' => 'Data Deleted Successfully', 'result'=> '']);
        }
        else
        {
            return response()->json(['status'=>'error', 'code'=> 403, 'message' => 'Data Not Found', 'result'=> '']);      
        }
    }
}
