<?php

namespace App\Http\Controllers\Api;


use App\Models\Cpu;
use App\Models\Display;
use App\Models\Graphic;
use App\Models\Memory;
use App\Models\Product;
use App\Models\Ram;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('Admin', ['only' => ['store', 'destroy','update']]);
    }

    public function index()
    {
        try {
            $products = Product::with(['cpu', 'display', 'memory', 'ram', 'graphic'])->get();
            return response()->json([
                'status' => true,
                'products' => $products
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
            $cpu = Cpu::find($request->cpu_id);
            if (!$cpu) {
                return $this->response()->json([
                    'status' => false,
                    'message' => 'Cpu not found'
                ], 404);
            }
            $display = Display::find($request->display_id);
            if (!$display) {
                return response()->json([
                    'status' => false,
                    'message' => 'Display not found'
                ], 404);
            }
            $memory = Memory::find($request->memory_id);
            if (!$memory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Memory not found'
                ], 404);
            }
            $ram = Ram::find($request->ram_id);
            if (!$ram) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ram not found'
                ], 404);
            }
            $graphic = Graphic::find($request->graphic_id);
            if (!$graphic) {
                return response()->json([
                    'status' => false,
                    'message' => 'Graphic not found'
                ], 404);
            }
            if ($request->file('product_img')) {
                $url = Storage::disk('s3')->put('laptops/products_images', $request->file('product_img'));
                $full_url = Storage::disk('s3')->url($url);
                if (!$request->hasFile('product_img')) {
                    return response()->json([
                        'message' => 'file error',
                        'request' => $request
                    ], 404);
                }
            }


            $new_product = Product::create([
                'manufacturer' => $request->manufacturer,
                'price' => $request->price,
                'image' => $request->file('product_img') ? $full_url : '',
                'name' => $request->name,
                'model' => $request->model,
                'color' => $request->color,
                'weight' => $request->weight,
                'multimedia' => $request->multimedia,
                'dimensions' => $request->dimensions,
                'os' => $request->os,
                'description' => $request->description,
                'cpu_id' => $cpu->id,
                'display_id' => $display->id,
                'memory_id' => $memory->id,
                'ram_id' => $ram->id,
                'graphic_id' => $graphic->id
            ]);
            try {
                $new_cpu = Cpu::create([
                    'manufacturer' => $cpu->manufacturer,
                    'series' => $cpu->series,
                    'model' => $cpu->model,
                    'cores_value' => $cpu->cores_value,
                    'frequency' => $cpu->frequency,
                    'product_id' => $new_product->id,
                    'slug' => $cpu->slug
                ]);
                $new_display = Display::create([
                    'cover' => $display->cover,
                    'matrix' => $display->matrix,
                    'size' => $display->size,
                    'resolution' => $display->resolution,
                    'product_id' => $new_product->id,
                    'slug' => $display->slug
                ]);
                $new_graphic = Graphic::create([
                    'manufacturer' => $graphic->manufacturer,
                    'series' => $graphic->series,
                    'model' => $graphic->model,
                    'type' => $graphic->type,
                    'product_id' => $new_product->id,
                    'slug' => $graphic->slug
                ]);
                $new_memory = Memory::create([
                    'manufacturer' => $memory->manufacturer,
                    'type' => $memory->type,
                    'size' => $memory->size,
                    'product_id' => $new_product->id,
                    'slug' => $memory->slug
                ]);
                $new_ram = Ram::create([
                    'manufacturer' => $ram->manufacturer,
                    'type' => $ram->type,
                    'memory' => $ram->memory,
                    'product_id' => $new_product->id,
                    'slug' => $ram->slug
                ]);
            } catch (HttpResponseException $exception) {
                return response()->json([
                    'status' => false,
                    'message' => $exception->getMessage()
                ], $exception->getCode());
            }

            return response()->json([
                'status' => true,
                'new_product' => $new_product
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
        $product = Product::where('id', $id)->first()->with(['cpu', 'display', 'memory', 'ram', 'graphic']);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        try {
            return response()->json([
                'status' => true,
                'product' => $product->with('cpu')->get()->find($id)
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
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        if($request->hasFile('product_img')) {
            $url = Storage::disk('s3')->put('laptops/products_images', $request->file('product_img'));
            $full_url = Storage::disk('s3')->url($url);
            $product->image = $request->file('product_img') ? $full_url : '';
            $product->save();
        }
        $keys = $request->keys();
        $target = array_keys($product->toArray());
        $res = array_intersect($keys, $target);
        try {
            foreach ($res as $key => $value) {
                $product->$value = $request->$value;
            }
            $product->save();
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'product' => $product
            ]);
        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        try {
            $product->delete();
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
