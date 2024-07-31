<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Graphic;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class GraphicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $graphics = Graphic::all();
            return response()->json([
                'status' => true,
                'graphics' => $graphics
            ]);
        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
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
            $new_graphic = Graphic::create([
                'manufacturer' => $request->manufacturer,
                'series' => $request->series,
                'model' => $request->model,
                'type' => $request->type,
                'slug'=>$request->slug,
                'product_id' => $request->product_id
            ]);
            return response()->json([
                'status' => true,
                'new_graphic' => $new_graphic
            ]);
        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $graphic = Graphic::find($id);
        if (!$graphic) {
            return response()->json([
                'status' => false,
                'message' => 'Graphic not found'
            ], 404);
        }
        try {
            return response()->json([
                'status' => true,
                'graphic' => $graphic
            ]);
        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
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
        $graphic = Graphic::find($id);
        if (!$graphic) {
            return response()->json([
                'status' => false,
                'message' => 'Graphic not found'
            ], 404);
        }
        try {
            $graphic->delete();
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ]);
        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
