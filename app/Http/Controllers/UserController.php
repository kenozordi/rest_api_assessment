<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\ResponseFormat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        try {
            $users = User::all();
            return ResponseFormat::returnResponse("OK", null, $users);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return ResponseFormat::returnResponse("FAIL", "An Error Occured", $ex->getMessage());
        }
    }

    /**
     * Store a new user in database.
     *
     * @param  \App\Http\Requests\User\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        try {
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            return ResponseFormat::returnResponse("OK", null, $user);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return ResponseFormat::returnResponse("FAIL", "Cannot create new user", $ex->getMessage());
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id id of user to show
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                return ResponseFormat::returnResponse("OK", null, $user);
            } else {
                return ResponseFormat::returnResponse("FAIL", "User not found");
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return ResponseFormat::returnResponse("FAIL", "Cannot create new user", $ex->getMessage());
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\User\StoreUserRequest  $request
     * @param  int  $id id of the user to be updated
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $detailsToUpdate = $request->validated();

            $user = User::find($id);
            if ($user) {
                $user->update($detailsToUpdate);
                return ResponseFormat::returnResponse("OK", null, $user->refresh());
            } else {
                return ResponseFormat::returnResponse("FAIL", "User not found");
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return ResponseFormat::returnResponse("FAIL", "Cannot update user", $ex->getMessage());
        }
    }

    /**
     * toggle the specified user enable/disable.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggle($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $is_disabled = $user->is_disabled == 0 ? 1 : 0;     //toggle enable and disable
                $user->update(['is_disabled' => $is_disabled]);
                return ResponseFormat::returnResponse("OK", null, $user->refresh());
            } else {
                return ResponseFormat::returnResponse("FAIL", "User not found or already deleted");
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return ResponseFormat::returnResponse("FAIL", "Cannot update user", $ex->getMessage());
        }
    }



    //#########################################     JWT Auth functions     ######################################

    /**
     * This will log the user in and assign the user a token
     *
     * @param  \App\Http\Requests\User\LoginUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);
        }

        $response = [
            "token" => $token
        ];
        return ResponseFormat::returnResponse("OK", "Login Successfully", $response);
    }
}
