<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    const NAME = 'name';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const POSITION = 'position';
    const STATE_ID = 'state_id';
    const DISTRICT_ID = 'district_id';
    const TOWNSHIP_ID = 'township_id';

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'position' => 'required|string|max:255',
            'state_id' => 'required|integer',
            'district_id' => 'required|integer',
            'township_id' => 'required|integer',
        ]);

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make('123456');
            $user->position = $request->input('position');
            $user->state_id = $request->input('state_id');
            $user->district_id = $request->input('district_id');
            $user->township_id = $request->input('township_id');

            $user->save();
            return jsend_success(new UserResource($user), JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return jsend_error(__('api.saved-failed', ['model' => 'User']), $e->getCode(), ErrorType::SAVE_ERROR, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginUserRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = User::where('email', '=', $email)->first();

            if (is_null($user)) {
                return jsend_fail(['message' => 'User does not exists.'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                return jsend_fail(['message' => 'Invalid Credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user = Auth::user();
            $tokenResult = $user->createToken('IO Token');
            $access_token = $tokenResult->accessToken;
            $expiration = $tokenResult->token->expires_at->diffInSeconds(now());

            return jsend_success([
                'name' => $user->name,
                'email' => $user->email,
                'position' => $user->position,
                'token_type' => 'Bearer',
                'access_token' => $access_token,
                'expires_in' => $expiration
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Login Failed!', [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);
            return jsend_error(['message' => 'Invalid Credentials']);
        }
    }

    public function index()
    {

        $position = request()->input('position');
        $email = request()->input('email');

        $data = User::all();

        if (!is_null($position)) {
            $data = $data->where('position', '=', $position);
        }
        if (!is_null($email)) {
            $data = $data->where('email', '=', $email);
        }

        return response()->json(["status" => "success", "data" => UserResource::collection($data), "total" => count($data)]);
    }

    public function update(Request $request, User $user)
    {

        try {
            $user->name = $request->input(self::NAME);
            $user->email = $request->input(self::EMAIL);
            $user->position = $request->input(self::POSITION);
            $user->state_id = $request->input(self::STATE_ID);
            $user->district_id = $request->input(self::DISTRICT_ID);
            $user->township_id = $request->input(self::TOWNSHIP_ID);

            $user->save();

            return jsend_success(new UserResource($user), JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error(__('api.updated-failed', ['model' => class_basename($this->model)]), [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['model' => 'User']), $e->getCode(), ErrorType::SAVE_ERROR, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {

        try {
            $user->delete();

            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return jsend_error(["error" => 'Data Not Found.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return jsend_success(['message' => 'Successfully Logout.'], JsonResponse::HTTP_ACCEPTED);
    }

    public function user()
    {
        $user = Auth::user();

        return jsend_success(new UserResource($user), JsonResponse::HTTP_OK);
    }

    public function show(User $user)
    {
        return jsend_success(new UserResource($user));
    }
}
