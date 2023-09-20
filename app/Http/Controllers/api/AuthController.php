<?php



namespace App\Http\Controllers\API;

use App\Http\Controllers\api\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request)
    {
        // Validate user registration data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'device_name' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse( '' , $validator , 422);
        }

        $data = $request->except('image');
        if ($request->image) {
            $data['image'] = $this->uploadImage($request);
        } else {
            $data['image'] = "";
        }

        // // إنشاء مستخدم جديد
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_code = $request->code;
        $user->image = $data['image'];
        $user->type = "user";
        $user->save();

        // Generate a token for the new user
        $device_name = $request->input('device_name', $request->userAgent());
        $token = $user->createToken($device_name)->plainTextToken;

       /* return $this->apiResponse([
            'data' => $user,
            'token' => $token,
            // 'token_type' => $user->type,
            'type' => $user->type,
        ],
         'ok', 201); */
        return $this->apiResponse(
            [
                'code' => 1,
                'user' => $user,
                'access_token' => $token,
            ],
            'ok', 201);
    }


    public function login(Request $request)
    {
       /* // التحقق من بيانات تسجيل الدخول
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            // نجحت عملية تسجيل الدخول
            $user = Auth::user();
            // $token = $user->createToken('auth_token')->accessToken;
            $token = $user->createToken('auth_token')->plainTextToken;
            // $token = sha1($user->id . time());

            return $this->apiResponse([
                'user' => $user,
                'token' => $token ,
            ],
             'ok', 200);
        }

        // فشلت عملية تسجيل الدخول
        return $this->apiResponse([ ],
        'The Email and Password is error', 401);
    } */

   /* public function logout()
    {
        // حذف جميع توكنات المستخدم
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->apiResponse([ ],
         'Logged out successfully', 200);
      }
   */


        // Validate user login data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }
        // Attempt to authenticate the user
        // $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // $device_name = $request->input('device_name', $request->userAgent());
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->apiResponse([
                'code' => 1,
                'user' => $user,
                'access_token' => $token,
            ],
             'ok', 200);

            // return Response::json([
            //     'code' => 1,
            //     'user' => $user,
            //     'access_token' => $token,
            // ], 200);
        }

        return $this->apiResponse([
            'code' => 0,
            'message' => 'Invalid credentials',
        ],
         'The Email and Password is error', 401);
        // return Response::json([
        //     'code' => 0,
        //     'message' => 'Invalid credentials',
        // ], 401);
    }


    public function logOut(Request $request, $token = null)
    {
        $user = $request->user();

        // If no specific token is provided, revoke all tokens
       /* if ($token === null) {
            $user->tokens()->delete();

            return Response::json([
                'code' => 1,
                'message' => 'All tokens revoked successfully',
            ], 200);
        }
      */

        // Attempt to find and revoke the specified token
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (
            $personalAccessToken &&
            $user->id == $personalAccessToken->tokenable_id &&
            get_class($user) == $personalAccessToken->tokenable_type
        ) {
            $personalAccessToken->delete();

            return $this->apiResponse([
                'code' => 1,
                'message' => 'Token revoked successfully',
            ],
             'successfully', 200);

            // return Response::json([
            //     'code' => 1,
            //     'message' => 'Token revoked successfully',
            // ], 200);
        }

        // If the specified token is not found or does not belong to the user, return an error

        return $this->apiResponse([
            'code' => 0,
            'message' => 'Invalid token',
        ],
         'Invalid', 401);
        // return Response::json([
        //     'code' => 0,
        //     'message' => 'Invalid token',
        // ], 401);
    }
}



