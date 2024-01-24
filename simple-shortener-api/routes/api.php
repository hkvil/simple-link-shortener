<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Registration
Route::post('/register', function (Request $request) {
   try{
     $incomingFields = $request->validate([
        'username' => ['required'],
        'email' => ['required'],
        'password' => ['required'],
    ]);

    $user = User::create($incomingFields);

    return response()->json([
        'message' => 'User registration successful',
        'error' => false,
        'data' => $user
    ], 200);

   }catch(Exception $e){
    return response()->json([
        'message' => 'User registration failed!',
        'error' => $e->getMessage(),
        'data' => []
    ], 409);
   }

});

//Login
Route::post('/login', function (Request $request) {
    try{
        $incomingFields = $request->validate([
           'username' => ['required'],
           'password' => ['required'],
       ]);

       $user = User::where('username',$incomingFields['username'])->first();

       if(auth()->attempt($incomingFields)){
        $token = $user->createToken('auth_token')->plainTextToken;

           return response()->json([
               'message' => 'User login successful',
               'error' => false,
               'data' => [
                   'user' => $user,
                   'token' => $token
               ]
           ], 200);
        }else{
            return response()->json([
                'message' => 'User login failed!',
                'error' => 'Invalid Credentials',
                'data' => []
            ], 401);
        }

      }catch(Exception $e){
       return response()->json([
           'message' => 'User login failed!',
           'error' => $e->getMessage(),
           'data' => []
       ], 409);
      }

   });
