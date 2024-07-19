<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with(['cpu','display','memory','ram','graphic'])->get();
            return response()->json([
                'status'=>true,
                'products'=>$products
            ]);
        }  catch (HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ],$exception->getCode());
        }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $new_product = Product::create([
//                'image'=>$request->image,
                'name'=>$request->name,
                'model'=>$request->model,
                'color'=>$request->color,
                'weight'=>$request->weight,
                'multimedia'=>$request->multimedia,
                'dimensions'=>$request->dimensions,
                'os'=>$request->os,
                'cpu_id'=>$request->cpu_id
//                ,'display_id'
//                ,'memory_id'
//                ,'ram_id'
//                ,'graphic_id'
            ]);
//            dd($new_product);
            return response()->json([
                'status'=>true,
                'new_product'=>$new_product
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ],$exception->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status'=>false,
                'message'=>'Product not found'
            ],404);
        }

        try{
            return response()->json([
                'status'=>true,
                'product'=>$product->with(['cpu','display'])->get()
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ],$exception->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status'=>false,
                'message'=>'Product not found'
            ],404);
        }
        try{
            $product->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Success'
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ],$exception->getCode());
        }
    }
}
