<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['store', 'destroy', 'update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::paginate(10);
            return response()->json([
                'status' => true,
                'users' => $users
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
        $user = new User();
        $full_url = '';
        if ($request->hasFile('user_img')) {
            $url = Storage::disk('s3')->put('laptops/avatars', $request->file('user_img'));
            $full_url = Storage::disk('s3')->url($url);
            $user->img = $full_url;
        }
        $keys = $request->keys();
        $username = User::where('username', $request->username)->first();
        $email = User::where('email', $request->email)->first();
        if ($request->username && $username) {
            return response()->json([
                'status' => false,
                'message' => 'Користувач з таким логіном вже існує, сробуйте інший варіант логіну'
            ], 500);
        }
        if ($request->email && $email) {
            return response()->json([
                'status' => false,
                'message' => 'Такий email вже існує, спробуйте інший варіант'
            ], 500);
        }
        try {
            foreach ($keys as $key => $value) {
                if ($value === 'is_admin') {
                    $user->$value = $request->$value === 'true' ? 1 : 0;
                } elseif($value !== 'user_img') {
                    $user->$value = $request->$value;
                }
            }
            $user->save();
            return response()->json([
                'status' => true,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'user' => $user
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
        if ($request->hasFile('user_img')) {
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

        $username = User::where('username', $request->username)->first();
        $email = User::where('email', $request->email)->first();
        if ($request->username && $username) {
            return response()->json([
                'status' => false,
                'message' => 'Користувач з таким логіном вже існує, сробуйте інший варіант логіну'
            ], 500);
        }
        if ($request->email && $email) {
            return response()->json([
                'status' => false,
                'message' => 'Такий email вже існує, спробуйте інший варіант'
            ], 500);
        }


        try {
            foreach ($res as $key => $value) {
                if ($value === 'is_admin') {
                    $user->$value = $request->$value === 'true' ? 1 : 0;
                } else
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

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
        try {
            $user->delete();

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
