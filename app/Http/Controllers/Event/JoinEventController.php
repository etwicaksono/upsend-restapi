<?php

namespace App\Http\Controllers\Event;

use App\RegisterEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class JoinEventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $userId = request('user_id');
        $eventId = request('event_id');

        $update = RegisterEvent::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->update([
                'is_user_come' => 1
            ]);

        if($update > 0){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'You Come in Event'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request!'
            ], Response::HTTP_OK);
        }
    }
}
