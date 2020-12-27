<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);


        if(!$token = Auth::attempt(request()->only('email', 'password'))) {
            return response("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', request('email'))->first();
        $data = json_decode($user, true);
        $data['token'] = $token;
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Login Successfully',
            'data' => $data
        ], Response::HTTP_OK);
    }
}
