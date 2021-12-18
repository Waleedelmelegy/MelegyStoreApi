<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportController extends Controller
{

    public function index(Request $request)
    {

        if ($request->bearerToken()) {
            $users = User::all();
            return response()->json(['status' => 200, 'data' => $users], 200);
        }
        
    }
   

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'phone' => 'required|unique:users|min:11|max:11|regex:/(01)[0-9]{9}/ ',
            'type' => 'required',
            'address' => 'required',
            'shipping_address' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'message' => $validator->messages()], 201);
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'type' => $request->type,
                'address' => $request->address,
                'shipping_address' => $request->shipping_address,
                'password' => bcrypt($request->password)
            ]);
            $token = $user->createToken('TutsForWeb')->accessToken;

            return response()->json(['status' => 200, 'user' => auth()->user(), 'token' => $token], 200);
        }

    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        if ($request->bearerToken()) {
            return response()->json(['status' => 201, 'data' => auth()->user(), 'token' => $request->header('Authorization')], 201);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->bearerToken()) {
            $users = User::find($id);
            if (!$users) {
                return response()->json(['status' => 204, 'message' => "no user with id = $id founded"], 204);
            } else {
                $updated = $users->fill($request->all())->save();

                if ($updated) {
                    return response()->json([
                        'status' => 202,
                        'data' => $users,
                        'success' => true,
                    ], 202);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'user could not be updated',
                    ], 204);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->bearerToken()) {
            $users = User::find($id);
            if (!$users) {
                return response()->json(['status' => 204, 'data' => "there is no user available with id = $id "], 204);
            } else {
                $users->delete();
                return response()->json(['status' => 202, 'message' => 'the user with id = $id was deleted success'], 202);
            }
        }
    }

    public function UserByID(Request $request, $id)
    {
        if ($request->bearerToken()) {
            $users = User::where('id', $id)->get();
            // return $users;exit;
            if (!$users->isEmpty()) {
                return response()->json(['status' => 201, 'data' => $users[0]], 201);
            } else {
                return response()->json(['status' => 204, 'data' => "there is no user available with id = $id "], 204);
            }
        }
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request, Response $response)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $user = User::where("email", $request->email)->get();
        if($user->isEmpty()){
            return response()->json(['status' => 202, 'message' => "email is not exist"], 202);
        }else{
            if (auth()->attempt($credentials)) {
                $token = auth()->user()->createToken('TutsForWeb')->accessToken;
                return response()->json(['status' => 201, 'data' => auth()->user(), 'token' => $token], 201);
            } else {
                return response()->json(['status' => 202, 'message' =>  'password is not correct'], 202);
            }
        }
        
    }

    public function logoutApi(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $token = $request->user()->token();
            $token->revoke();
            $response = ['message' => 'You have been successfully logged out!', 'status' => 201];
            return response($response, 201);
        }
        return response($response, 200);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 401, 'message' => 'UnAuthorised'], 401);
    }
}
