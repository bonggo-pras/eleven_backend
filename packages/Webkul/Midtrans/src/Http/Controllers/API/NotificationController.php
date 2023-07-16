<?php

namespace Webkul\Midtrans\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Webkul\Midtrans\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $notification = new Notification;
        $serverKey = core()->getConfigData("sales.paymentmethods.midtrans.server_key");

        $this->validate($request, [
            'signature_key' => 'required',
            'order_id' => 'required',
            'status_code' => 'required',
            'gross_amount' => 'required',
        ]);

        $notificationValid = $notification->validateNotification($request->order_id, $request->status_code, $request->gross_amount, $serverKey, $request->signature_key);

        if (!$notificationValid) {
            return response()->json(["message" => "invalid request"], 401);
        }

        Event::dispatch('payment.midtrans.notification.received', json_decode($request->getContent()));

        return response()->json(["message" => "Notification accepted"], 200);
    }
}
