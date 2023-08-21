<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherController extends Controller
{
    public function isInternetConnected() {
        $ip = gethostbyname("www.google.com");
        return $ip !== "www.google.com";
    }
    public function triggerPusherEvent($channel, $event) {
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        ];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $data = [];
        $pusher->trigger($channel, $event, $data);
    }
}
