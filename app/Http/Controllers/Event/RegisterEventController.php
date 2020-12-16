<?php

namespace App\Http\Controllers\Event;

use App\Event;
use App\RegisterEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterEventController extends Controller
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
        // code validation
        request()->validate([
            'code_event' => 'required',
            'user_id' => 'required'
        ]);

        $codeEvent = request('code_event');
        $userId = request('user_id');      

        $dataEvent = Event::where('code', $codeEvent)->first();

        if($dataEvent == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Event not Found'
            ], Response::HTTP_OK);
        } else {
            RegisterEvent::create([
                'user_id' => $userId,
                'event_id' => $dataEvent->id
            ]);
    
            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'You Register at event '. $dataEvent->name
            ], Response::HTTP_CREATED);
        }
    }
}
