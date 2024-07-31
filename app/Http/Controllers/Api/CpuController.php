<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cpu;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CpuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cpu_list = Cpu::all();
        try{
            return response()->json([
                'status'=>true,
                'cpu_list'=>$cpu_list
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>true,
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

        $new_cpu = Cpu::create([
            'manufacturer'=>$request->manufacturer,
            'series'=>$request->series,
            'model'=>$request->model,
            'cores_value'=>$request->cores_value,
            'frequency'=>$request->frequency,
            'slug'=>$request->slug,
            'product_id'=>$request->product_id
        ]);
        return response()->json([
            'status'=>true,
            'new_cpu'=>$new_cpu
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
        $cpu = Cpu::find($id);
        if(!$cpu){
            return response()->json([
                'status'=>false,
                'message'=>'Cpu not found'
            ],404);
        }

        try{
            return response()->json([
                'status'=>true,
                'cpu'=>$cpu
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ]);
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
        $cpu = Cpu::find($id);
        if(!$cpu){
            return response()->json([
                'status'=>false,
                'message'=>'Cpu not found'
            ],404);
        }
        try{
            $cpu->delete();
            return response()->json([
                'status'=>true,
                'cpu'=>'Success'
            ]);
        }catch(HttpResponseException $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ]);
        }
    }
}
