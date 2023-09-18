<?php



namespace App\Http\Controllers\API;

use App\Http\Controllers\api\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request)
    {
        // التحقق من صحة البيانات المرسلة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
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

        // إنشاء توكن للمستخدم
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse([
            'data' => $user,
            'token' => $token,
            // 'token_type' => $user->type,
            'type' => $user->type,
        ],
         'ok', 201);
    }

    public function login(Request $request)
    {
        // التحقق من بيانات تسجيل الدخول
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
    }

    public function logout()
    {
        // حذف جميع توكنات المستخدم
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->apiResponse([ ],
         'Logged out successfully', 200);
    }
}



/* namespace App\Http\Controllers\API;

    use App\Http\Controllers\api\ApiResponseTrait;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    // use Auth;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    class AuthController extends Controller
    {
        use ApiResponseTrait;

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors());
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()
                ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
        }
        /*
            public function login(Request $request)
            {
                if (!FacadesAuth::attempt($request->only('email', 'password')))
                {
                    return response()
                        ->json(['message' => 'Unauthorized'], 401);
                }

                $user = User::where('email', $request['email'])->firstOrFail();

                $token = $user->createToken('auth_token')->plainTextToken;

                return $this->apiResponse(
                    ['message' => 'Hi '.$user->name.' welcome to home','access_token' => $token, 'token_type' => 'Bearer' ],
                'ok', 200,
                    );

                    // return $this->apiResponse([
                    //     'category' => $category,
                    //     'categories' => $categories,
                    // ],
                    //  'ok', 200);
                }
        */
    /*     public function login(Request $request)
        {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('multi-store')->accessToken;

                return response()->json(['token' => $token , 'to' => $user->accessToken ], 200);
            }

            return response()->json(['message' => 'Unauthorized'], 401);
        }




        // method for user logout and delete token
        public function logout()
        {
            auth()->user()->tokens()->delete();

            return [
                'message' => 'You have successfully logged out and the token was successfully deleted'
            ];
        }
    }
*/

