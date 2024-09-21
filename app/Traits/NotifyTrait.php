<?php

namespace App\Traits;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

trait NotifyTrait
{
    /**
     * Send a Firebase Cloud Messaging (FCM) notification.
     *
     * @param string      $fcmToken  The recipient's FCM token.
     * @param string      $title     The title of the notification.
     * @param string      $body      The body/content of the notification.
     * @param array       $data      Optional data payload.
     * @param string|null $imageUrl  Optional image URL for the notification.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function sendFcmNotification(
        string $fcmToken,
        string $title,
        string $body,
        array $data = [],
    ): bool {
        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($data);

            $messaging = Firebase::messaging();
            $messaging->send($message);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
