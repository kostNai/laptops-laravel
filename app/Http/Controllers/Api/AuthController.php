<?php

namespace App\Http\Controllers\Api;


use App\Http\Middleware\auth;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['refresh']]);
    }
    public function register(Request $request){
        $username = $request->username;
        $password = Hash::make($request->password);
        $email = $request->email;


        if(!$username || !$password || !$email){
            return response()->json([
                'status'=>false,
                'message'=>'Необхідно заповнити всі поля'
            ],500);
        }
        if(User::where('username',$username)->first()){
            return response()->json([
                'status'=>false,
                'message'=>'Користувач з таким логіном вже існує'
            ],500);
        }
        if(strlen($request->password)<4){
            return response()->json([
                'status'=>false,
                'message'=>'Пароль має буди не менше 4 символів'
            ],500);
        }
        if(User::where('email',$email)->first()){
            return response()->json([
                'status'=>false,
                'message'=>'Ця електронна адреса вже зайнята'
            ],500);
        }
        try {
            $user = User::create([
                'username'=>$username,
                'email'=>$email,
                'password'=>$password,
            ]);

            return response()->json([
                'status'=>true,
                'user'=>$user,
            ]);}catch(HttpResponseException $exception){
                return response()->json([
                    'status'=>false,
                    'message'=>$exception->getMessage()
                ],$exception->getCode());
        }

    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if(!$request->username || !$request->password){
            return response()->json([
                'status'=>false,
                'message'=>'Усі поля мають бути заповнені'
            ],500);
        }

        if(!User::where('username',$request->username)->first()){
            return response()->json([
                'status'=>false,
                'message'=>'Невірний логін'
            ],500);
        }

        $user = User::where('username',$request->username)->first();
        if(strlen($request->password)<4){
            return response()->json([
                'status'=>false,
                'message'=>'Пароль має буди не менше 4 символів'
            ],500);
        }

        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                'status'=>false,
                'message'=>'Невірний пароль'
            ],500);
        }
        $new_access_token = Token::where('user_id',$user->id)->first();
        $refresh_credentials = request(['username', 'password','id']);
        $access_credentials = request(['username', 'password']);
        $new_refresh_token = auth()->claims(['username' => $user->username,'email'=>$user->email,'id'=>$user->id])->attempt($refresh_credentials);
        $access_token = auth()->claims(['username' => $user->username,'email'=>$user->email,'name'=>$user->name,])->setTTL(60)->attempt($access_credentials);
//        dd($access_token);




        if(!$new_access_token){
            $refresh_token = Token::create([
                'refresh_token'=>$new_refresh_token,
                'user_id'=>$user->id
            ]);
            $user->token_id=$refresh_token->id;
            $refresh_token->save();
            $user->save();

            return response()->json([
                    'status'=>true,
                    'access_token'=>$access_token,
                    'user'=>$user
                ]
            );
        }
        $new_access_token->refresh_token = $new_refresh_token;
        $new_access_token->save();
        return response()->json([
                'status'=>true,
                'access_token'=>$access_token,
                'user'=>$user
            ]
        );
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $current_user = auth()->user();

        $user = User::where('username',$current_user->username)->first();
        $token = Token::where('user_id',$user->id)->first();

        if(!$token){
            return response()->json([
                'status'=>false,
                'message'=>'Refresh token not found'
            ],404);
        }
        $new_token = auth()->refresh();
        $token->refresh_token = $new_token;
        $user->token_id = $token->id;
        $access_token = auth()->claims(['username' => $user->username,'email'=>$user->email,'name'=>$user->name])->setTtl(60)->refresh();

        $token->save();
        $user->save();
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'access_token'=>$access_token
        ]);

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

}
