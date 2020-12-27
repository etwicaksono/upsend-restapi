<?php

namespace App\Http\Controllers\Event;

use App\Event;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use App\RegisterEvent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\URL;

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
            $baseUrl = URL::to("/img") . "/";
            $data = [];
            foreach ($event as $key => $value) {
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
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'This data',
            'data' => $data
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
        $nameOfCode = request('user_id') . "_" . time() . "_" . $this->_generateRandomString();

        Event::create([
            'user_id' => request('user_id'),
            'name' => request('name'),
            'code' => $nameOfCode,
            'capasity' => request('capasity'),
            'description' => request('description'),
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

            $baseUrl = URL::to("/img") . "/";
            $result = [
                'id' => $data->id,
                'createBy_userId' => $data->user_id,
                'name' => $data->name,
                'code' => $data->code,
                'url_qr_code' => $baseUrl . "events_qrcode/" . $data->code . ".png",
                'capasity' => $data->capasity,
                'description' => $data->description,
                'image_url' => $baseUrl . "events_image_poster/" . $data->image,
                'status' => $data->status,
                'start_date' => $data->start_date,
                'due_date' => $data->due_date,
                'participant' => $participant,
                'participant_is_coming' => $participantIsComing
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
                    'data' => $result
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
    public function update(EventRequest $request)
    {
        if (request('id') == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
            ], Response::HTTP_OK);
        } else {
            $baseUrl = $_SERVER['DOCUMENT_ROOT'] . "/img/events_image_poster/";
            $imagePoster = request('image');
            $dataCurrent = Event::where('id', request('id'))->first();
            if ($imagePoster != null) {
                unlink($baseUrl . $dataCurrent->image);
                $imagePosterName = time() . "_" . $imagePoster->getClientOriginalName();
                $tujuan_upload = 'img/events_image_poster';
                $imagePoster->move($tujuan_upload, $imagePosterName);
            } else {
                $imagePosterName = $dataCurrent->image;
            }

            $data = [
                'user_id' => request('user_id'),
                'name' => request('name'),
                'capasity' => request('capasity'),
                'description' => request("description"),
                'image' => $imagePosterName,
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
    public function destroy($id)
    {
        if ($id == null) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
            ], Response::HTTP_OK);
        } else {
            $delete = Event::where('id', $id)->delete();
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

        $search = Event::select('events.*')
            ->join('register_events', 'register_events.event_id', '=', 'events.id')
            ->where('register_events.user_id', $userId)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();

        $baseUrl = URL::to("/img") . "/";
        $data = [];
        foreach ($search as $key => $value) {
            $result = [
                'id' => $value->id,
                'createBy_userId' => $value->user_id,
                'name' => $value->name,
                'code' => $value->code,
                'url_qr_code' => $baseUrl . "events_qrcode/" . $value->code . ".png",
                'description' => $value->description,
                'capasity' => $value->capasity,
                'image_url' => $baseUrl . "events_image_poster/" . $value->image,
                'status' => $value->status,
                'start_date' => $value->start_date,
                'due_date' => $value->due_date
            ];
            array_push($data, $result);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'This data!',
            'data' => $data
        ], Response::HTTP_OK);
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

    private function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
