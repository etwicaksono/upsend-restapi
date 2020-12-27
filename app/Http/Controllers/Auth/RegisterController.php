<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'username' => request('username'),
            'firstname' => request('firstname'),
            'lastname' => request('lastname'),
            'email' => request('email'),
            'access_id' => 1,
            'role_id' => 2,
            'password' => bcrypt(request('password'))
        ]);

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => "Registration Successfully",
            'data' => $user
        ], Response::HTTP_CREATED);
    }
}
