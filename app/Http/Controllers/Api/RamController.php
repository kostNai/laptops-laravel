<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ram;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class RamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $ram_list = Ram::all();
            return response()->json([
                'status'=>true,
                'ram_list'=>$ram_list
            ]);
        }catch(HttpResponseException $exception){
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
        try{
            $new_ram = Ram::create([
                'manufacturer'=>$request->manufacturer,
                'type'=>$request->type,
                'memory'=>$request->memory,
                'slug'=>$request->slug,
                'product_id'=>$request->product_id
            ]);
            return response()->json([
                'status'=>true,
                'new_ram'=>$new_ram
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
        try{
            $ram = Ram::find($id);
            if(!$ram){
                return response()->json([
                    'status'=>false,
                    'message'=>'Ram not found'
                ]);
            }
            return response()->json([
                'status'=>true,
                'ram'=>$ram
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
        try{
            $ram = Ram::find($id);
            if(!$ram){
                return response()->json([
                    'status'=>false,
                    'message'=>'Ram not found'
                ]);
            }
            $ram->delete();
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
