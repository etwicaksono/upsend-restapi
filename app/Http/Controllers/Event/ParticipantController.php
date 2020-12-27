<?php

namespace App\Http\Controllers\Event;

use App\Event;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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

        $listOfParticipant = User::join('register_events', 'register_events.user_id', '=', 'users.id')
            ->where('register_events.event_id', $eventId)
            ->get();

        if ($listOfParticipant == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data Participant not found',
                'data' => $listOfParticipant
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

        $listOfParticipant = User::join('register_events', 'register_events.user_id', '=', 'users.id')
            ->where('register_events.event_id', $eventId)
            ->where('register_events.is_user_come', 1)
            ->get();

        if ($listOfParticipant == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data Participant not found',
                'data' => $listOfParticipant
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Participant',
                'data' => $listOfParticipant
            ], Response::HTTP_OK);
        }
    }

    public function listEventByFollowing()
    {
        $userId = request('user_id');

        $listOfEvent = Event::select('events.*')
            ->join('register_events', 'register_events.event_id', '=', 'events.id')
            ->where('register_events.user_id', $userId)
            ->get();
        $baseUrl = URL::to("/img") . "/";
        $data = [];
        if ($listOfEvent == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data Event not found'
            ], Response::HTTP_OK);
        } else {
            foreach ($listOfEvent as $key => $value) {
                $result = [
                    'id' => $value->id,
                    'createBy_userId' => $value->user_id,
                    'name' => $value->name,
                    'code' => $value->code,
                    'url_qr_code' => $baseUrl . "events_qrcode/" . $value->code . ".png",
                    'capasity' => $value->capasity,
                    'description' => $value->description,
                    'image_url' => $baseUrl . "events_image_poster/" . $value->image,
                    'status' => $value->status,
                    'start_date' => $value->start_date,
                    'due_date' => $value->due_date
                ];
                array_push($data, $result);
            }
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Event',
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
}
