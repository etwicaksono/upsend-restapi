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

        if ($dataEvent == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Event not Found'
            ], Response::HTTP_OK);
        } else {

            // code
            $checkRegistrationEvent = RegisterEvent::where([
                "user_id" => $userId,
                "event_id" => $dataEvent->id
            ])->first();

            if ($checkRegistrationEvent == null) {

                // goes to registration
                RegisterEvent::create([
                    'user_id' => $userId,
                    'event_id' => $dataEvent->id
                ]);

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'You Register at event ' . $dataEvent->name,
                    'data' => $dataEvent
                ], Response::HTTP_CREATED);
            } else {

                $checkIfCome = RegisterEvent::where('user_id', $userId)
                    ->where('event_id', $dataEvent->id)->first();

                if ($checkIfCome->is_user_come == 1) {
                    return response()->json([
                        'status' => Response::HTTP_OK,
                        'message' => 'Anda Sudah hadir di event ini',
                    ], Response::HTTP_OK);
                } else {
                    // come
                    $update = RegisterEvent::where('user_id', $userId)
                        ->where('event_id', $dataEvent->id)
                        ->update([
                            'is_user_come' => 1
                        ]);

                    if ($update > 0) {
                        return response()->json([
                            'status' => Response::HTTP_OK,
                            'message' => 'You Come in Event',
                            'data' => $checkRegistrationEvent
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => Response::HTTP_BAD_REQUEST,
                            'message' => 'Bad Request!',
                            'data' => $checkRegistrationEvent
                        ], Response::HTTP_OK);
                    }
                }
            }
        }
    }
}
