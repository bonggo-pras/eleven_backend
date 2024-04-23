<?php

namespace Webkul\Midtrans;

class Notification
{
    public function validateNotification($orderId, $statusCode, $grossAmount, $serverKey, $signatureKey)
    {
        $validHash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $signatureKey === $validHash;
    }
}
