<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    // Get user by id 
  


     

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'user' => Auth::user(),
        ]);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'surname' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'password_confirmation' => 'required|string',
                'role' => 'required|string|in:admin,user,owner',
                'date_of_birth' => 'required|date',
                'city' => 'required|string|max:100',
                'phone_number' => 'required|string|max:20',
                'photo' => 'nullable|string',
                'availability' => 'required|boolean',
                'transport' => 'required|boolean',
                'club_id' => 'nullable|exists:clubs,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            $verificationUrl = route('verify.email', ['email' => $user->email]);
            Mail::send([], [], function ($message) use ($user, $verificationUrl) {
                $message->to($user->email)
                    ->subject('Verification de votre email')
                    ->html("<h2>{$user->name}! Merci de vous être inscrit sur notre site</h2>
                            <h4>Veuillez vérifier votre email pour continuer...</h4>
                            <a href='{$verificationUrl}'>Cliquez ici</a>");
            });

            return response()->json([
                'message' => 'User successfully registered. Please verify your email.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User registration failed! Please try again.',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->query('email'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->isActive) {
            return response()->json([
                'success' => true,
                'message' => 'Account already activated'
            ]);
        }

        $user->isActive = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Account activated successfully'
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh();
            return $this->createNewToken($newToken);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 401);
        }
    }

    public function getall(Request $request)
    {
        $users = User::all(); // Fetch all users
        if ($users->isEmpty()) {
            return response()->json(['error' => 'No user found'], 404);
        } else {
            return response()->json($users); // Return the users
        }
    }

    public function getUserProfile()
    {
        try {
            $user = Auth::user();

            if ($user) {
                return response()->json([
                    'message' => 'User profile retrieved successfully.',
                    'user' => $user
                ], 200);
            } else {
                return response()->json(['error' => 'No authenticated user found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getUserById($id)
    {
        $user = User::with('club')->find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'No user found'], 404);
        }
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }

    public function editProfile(Request $request, $id)
    {
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|between:2,100',
            'surname' => 'sometimes|required|string|between:2,100',
            'email' => 'sometimes|required|string|email|max:100|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|confirmed|min:6',
            'date_of_birth' => 'sometimes|required|date',
            'city' => 'sometimes|required|string|max:100',
            'phone_number' => 'sometimes|required|string|max:20',
            'photo' => 'nullable|string',
            'availability' => 'sometimes|required|boolean',
            'transport' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $data = $validator->validated();
        unset($data['isActive'], $data['role']);

        $user->update($data);

        return response()->json([
            'message' => 'Profile successfully updated.',
            'user' => $user
        ], 200);
    }
}