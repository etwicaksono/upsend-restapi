<?php

namespace App\Http\Controllers\Event;

use App\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function registration()
    {
        $eventId = request('event_id');

        $listOfParticipant = Event::where('event_id', $eventId)->get();

        if($listOfParticipant == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data Participant not found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Participant',
                'data' => $listOfParticipant
            ], Response::HTTP_OK);
        }
    }

    public function come()
    {
        $eventId = request('event_id');

        $listOfParticipant = Event::where('event_id', $eventId)->where('is_user_come', 1)->get();

        if($listOfParticipant == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data Participant not found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Participant',
                'data' => $listOfParticipant
            ], Response::HTTP_OK);
        }
    }
}
