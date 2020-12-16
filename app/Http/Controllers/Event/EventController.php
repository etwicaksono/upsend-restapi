<?php

namespace App\Http\Controllers\Event;

use App\Event;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use App\RegisterEvent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\QrCode;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = request('user_id');

        if ($userId == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
            ], Response::HTTP_OK);
        } else {
            $event = Event::where('user_id', $userId)->get();
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'This data',
            'data' => $event
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        // request image poster for upload
        $imagePoster = request('image');
        $imagePosterName = time() . "_" . $imagePoster->getClientOriginalName();
        $tujuan_upload = 'img/events_image_poster';
        $imagePoster->move($tujuan_upload, $imagePosterName);

        // generate code for QRCode
        $nameOfCode = request('user_id') . "." . substr("%Y", time(), 5);

        Event::create([
            'user_id' => request('user_id'),
            'name' => request('name'),
            'code' => $nameOfCode,
            'capasity' => request('capasity'),
            'image' => $imagePosterName,
            'status' => 1,
            'start_date' => request('start_date'),
            'due_date' => request('due_date'),
        ]);

        $this->_generateQrCode($nameOfCode);

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => 'Create Event Successful!',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $eventId = request('id');
        if ($eventId == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'bad Request!',
            ], Response::HTTP_OK);
        } else {
            $data = Event::where('id', $eventId)->first();
            $participant = RegisterEvent::where('event_id', $eventId)->count();
            $participantIsComing = RegisterEvent::where('event_id', $eventId)
                ->where('is_user_come', 1)
                ->count();

            $response = [
                'participant' => $participant,
                'participant_is_coming' => $participantIsComing,
                'data' => $data
            ];

            if ($data == null) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Data Not Found!',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'This Data',
                    'result' => $response
                ], Response::HTTP_OK);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {

        if (request('id') == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
            ], Response::HTTP_OK);
        } else {

            $data = [
                'user_id' => request('user_id'),
                'name' => request('name'),
                'code' => request('code'),
                'capasity' => request('capasity'),
                'image' => request('image'),
                'status' => 1,
                'start_date' => request('start_date'),
                'due_date' => request('due_date'),
            ];

            $update = Event::where('id', request('id'))
                ->update($data);

            if ($update > 0) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Update Successfully!',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Failed Update, Bad Request',
                ], Response::HTTP_OK);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if (request('id') == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
            ], Response::HTTP_OK);
        } else {
            $delete = Event::where('id', request('id'))->delete();
            if ($delete > 0) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Delete Event Successfully!',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Failed Delete, Bad Request',
                ], Response::HTTP_OK);
            }
        }
    }

    /**
     * Search event data from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchEvent()
    {
        $query = request('query');
        $userId = request('user_id');

        $search = Event::query()
            ->where('user_id', $userId)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();


        // if(empty($search)) {
        //     return "asdf";
        // } else {
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'This data!',
            'data' => $search
        ], Response::HTTP_OK);
        // }
    }


    /**
     * QrCode Generator.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    private function _generateQrCode($code = "")
    {
        $qrCode = new QrCode($code);

        $qrCode->setSize(500);
        $qrCode->setMargin(20);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN);

        // Save it to a file
        $path = public_path('img/events_qrcode/' . $code . '.png');
        $qrCode->writeFile($path);
    }
}
