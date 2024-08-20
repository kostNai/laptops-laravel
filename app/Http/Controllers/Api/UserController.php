<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['store', 'destroy','update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'status'=>true,
                'users'=>$users
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $user = User::find($id);

       if(!$user){
           return response()->json([
               'status'=>false,
               'message'=>'User not found'
           ],404);
       }

       return response()->json([
           'status'=>true,
           'user'=>$user
       ]);
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
        $user = User::find($id);
        if($request->hasFile('user_img')) {
            $url = Storage::disk('s3')->put('laptops/avatars', $request->file('user_img'));
            $full_url = Storage::disk('s3')->url($url);
            $user->img = $request->file('user_img') ? $full_url : '';
            $user->save();
        }

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        $keys = $request->keys();
        $target = array_keys($user->toArray());
        $res = array_intersect($keys, $target);

        $username = User::where('username',$request->username)->first();
        $email = User::where('email',$request->email)->first();
        if($request->username && $username){
           return response()->json([
               'status'=>false,
               'message'=>'Користувач з таким логіном вже існує, сробуйте інший варіант логіну'
           ], 500);
        }
        if($request->email && $email){
           return response()->json([
               'status'=>false,
               'message'=>'Такий email вже існує, спробуйте інший варіант'
           ],500);
        }


        try {
            foreach ($res as $key => $value) {
                $user->$value = $request->$value;
            }

            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'user' => $user
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
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status'=>false,
                'message'=>'User not found'
            ]);
        }
        try {
            $user->delete();

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
