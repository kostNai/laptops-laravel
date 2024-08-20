<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cpu;
use App\Models\Display;
use App\Models\Graphic;
use App\Models\Memory;
use App\Models\Product;
use App\Models\Ram;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdditionalController extends Controller
{
    public function getProductsByCpu(Request $request)
    {
        $cpu = Cpu::find($request->id);
        if (!$cpu) {
            return response()->json([
                'status' => false,
                'message' => 'Cpu not found'
            ], 404);
        }
        try {
            $products = $cpu->products()->with(['cpu', 'display', 'graphic', 'memory', 'ram'])->get();

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

    public function getProductsByDisplay(Request $request)
    {
        $display = Display::find($request->id);
        if (!$display) {
            return response()->json([
                'status' => false,
                'message' => 'Display not found'
            ], 404);
        }
        try {
            $products = $display->products()->with(['cpu', 'display', 'graphic', 'memory', 'ram'])->get();

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

    public function getProductsByGraphic(Request $request)
    {
        $graphic = Graphic::find($request->id);
        if (!$graphic) {
            return response()->json([
                'status' => false,
                'message' => 'Graphic not found'
            ], 404);
        }
        try {
            $products = $graphic->products()->with(['cpu', 'display', 'graphic', 'memory', 'ram'])->get();

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

    public function getProductsByMemory(Request $request)
    {
        $memory = Memory::find($request->id);
        if (!$memory) {
            return response()->json([
                'status' => false,
                'message' => 'Memory not found'
            ], 404);
        }
        try {
            $products = $memory->products()->with(['cpu', 'display', 'graphic', 'memory', 'ram'])->get();

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

    public function getProductsByRam(Request $request)
    {
        $ram = Ram::find($request->id);
        if (!$ram) {
            return response()->json([
                'status' => false,
                'message' => 'Ram not found'
            ], 404);
        }
        try {
            $products = $ram->products()->with(['cpu', 'display', 'graphic', 'memory', 'ram'])->get();

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

    public function getFilteredData(Request $request)
    {
        $model = 'App\Models\\' . $request->component;
        $unique = $model::all()->unique('slug');

        return response()->json([
            'status' => true,
            Str::lower($request->component . '_list') => $unique->values()
        ]);
    }

    public function getProductByName(string $name)
    {
        $product = Product::where('name', $name)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        return response()->json([
            'status' => true,
            'product' => $product
        ]);
    }

}
