<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cpu;
use App\Models\Display;
use App\Models\Graphic;
use App\Models\Memory;
use App\Models\Product;
use App\Models\Ram;
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
            $cpu = Cpu::find($request->cpu_id);
            if(!$cpu){
                return response()->json([
                    'status'=>false,
                    'message'=>'Cpu not found'
                ],404);
            }
            $display = Display::find($request->display_id);
            if(!$display){
                return response()->json([
                    'status'=>false,
                    'message'=>'Display not found'
                ],404);
            }
            $memory = Memory::find($request->memory_id);
            if(!$memory){
                return response()->json([
                    'status'=>false,
                    'message'=>'Memory not found'
                ],404);
            }
            $ram = Ram::find($request->ram_id);
            if(!$ram){
                return response()->json([
                    'status'=>false,
                    'message'=>'Ram not found'
                ],404);
            }
            $graphic = Graphic::find($request->graphic_id);
            if(!$graphic){
                return response()->json([
                    'status'=>false,
                    'message'=>'Graphic not found'
                ],404);
            }

            $new_product = Product::create([
//                'image'=>$request->image,
                'name'=>$request->name,
                'model'=>$request->model,
                'color'=>$request->color,
                'weight'=>$request->weight,
                'multimedia'=>$request->multimedia,
                'dimensions'=>$request->dimensions,
                'os'=>$request->os,
                'cpu_id'=>$cpu->id,
                'display_id'=>$display->id,
                'memory_id'=>$memory->id,
                'ram_id'=>$ram->id,
                'graphic_id'=>$graphic->id
            ]);

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
