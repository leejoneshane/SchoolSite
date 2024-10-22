<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\GameCharacterChannel;
use App\Events\GameDialogChannel;
use App\Events\GamePartyChannel;
use App\Events\GameRoomChannel;

class MessagerController extends Controller
{

    public function dialog(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        if ($request->has('code')) {
            $code = $request->input('code');
        } else {
            $code = null;
        }
        broadcast(new GameDialogChannel($from, $to, $code));
    }

    public function personal(Request $request)
    {
        $to = $request->input('to');
        if ($request->has('message')) {
            $message = $request->input('message');
        } else {
            $message = '';
        }
        broadcast(new GameCharacterChannel($to, $message));
    }

    public function party(Request $request)
    {
        $party_id = $request->input('party');
        $message = $request->input('message');
        broadcast(new GamePartyChannel($party_id, $message));
    }

    public function classroom(Request $request)
    {
        $room_id = $request->input('room');
        $message = $request->input('message');
        broadcast(new GameRoomChannel($room_id, $message));
    }

}
