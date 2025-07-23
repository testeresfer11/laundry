<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\Room;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use SendResponseTrait;

    public function joinRoom(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'room_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->messages());
            }

            $roomId = Room::create([
                'room_id' => $request->room_id,
            ]);

            \Log::info($roomId);

            return $this->apiResponse('success',200, __('messages.Room Joined!'));
        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return $this->apiResponse('error',500,$e->getMessage());
        }

    }

    public function sendMessage(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'room_id'   => 'required',
                'from_id'   => 'required|exists:users,id',
                'to_id'     => 'required|exists:users,id',
                'message'   => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse('error', 422, $validator->errors()->first());
            }

            $chat = chat::create([
                'room_id'   => $request->room_id,
                'from_id'   => $request->from_id,
                'to_id'     => $request->to_id,
                'message'   => $request->message,
            ]);
            \Log::info($chat);

            return $this->apiResponse('success',200, __('messages.Message sent!'), $chat);
        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }

    public function getMessages($roomId)
    {
        $messages = Chat::where('room_id', $roomId)->get();
        return $this->apiresponse('success', 200, __('messages.Chat Messages') .' '. __('messages.SUCCESS.FETCH_DONE'), $messages);
    }
}
