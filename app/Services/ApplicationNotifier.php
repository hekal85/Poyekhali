<?php

namespace App\Services;

use App\Mail\ApplicationReceived;
use App\Mail\ApplicationStatusUpdated;
use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * بيبعت إشعارات الطلبات على كل قناة متاحة (إيميل / تيليجرام / واتساب).
 * كل قناة مستقلة عن التانية - لو واحدة فشلت أو مش متظبطة، الباقي بيشتغل عادي
 * والطلب نفسه بيتحفظ في قاعدة البيانات في كل الحالات.
 */
class ApplicationNotifier
{
    public function notify(Application $application): void
    {
        $message = $this->receivedMessage($application);
        $this->sendEmail($application, fn () => new ApplicationReceived($application));
        $this->sendTelegram($message);
        $this->sendWhatsapp($application, $message);
    }

    public function notifyStatusChange(Application $application): void
    {
        $message = $this->statusChangeMessage($application);
        $this->sendEmail($application, fn () => new ApplicationStatusUpdated($application));
        $this->sendTelegram($message);
        $this->sendWhatsapp($application, $message);
    }

    private function receivedMessage(Application $application): string
    {
        return "تحية طيبة {$application->name}،\n\n"
            . "تم استلام طلبك بنجاح برقم: {$application->order_number}\n"
            . "الدولة: {$application->country->name_ar}\n"
            . "نوع التأشيرة: {$application->visaType->name_ar}\n\n"
            . "تقدر تتابع حالة طلبك في أي وقت من صفحة \"تتبع حالة الطلب\" على الموقع "
            . "برقم الطلب ورقم جواز السفر.\n\n"
            . "فريق بيخالي (Poyekhali)";
    }

    private function statusChangeMessage(Application $application): string
    {
        $statusLabel = Application::STATUS_LABELS_AR[$application->status] ?? $application->status;

        return "تحية طيبة {$application->name}،\n\n"
            . "في تحديث على طلبك رقم: {$application->order_number}\n"
            . "الحالة الجديدة: {$statusLabel}\n\n"
            . "فريق بيخالي (Poyekhali)";
    }

    private function sendEmail(Application $application, \Closure $mailableFactory): void
    {
        if (! $application->email) {
            return;
        }

        try {
            Mail::to($application->email)->send($mailableFactory());
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function sendTelegram(string $message): void
    {
        $token = config('notifications.telegram.bot_token');
        $chatId = config('notifications.telegram.chat_id');

        if (! $token || ! $chatId) {
            return; // البوت مش متظبط - تجاهل من غير ما توقف باقي الإشعارات
        }

        try {
            Http::timeout(8)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function sendWhatsapp(Application $application, string $message): void
    {
        $url = config('notifications.whatsapp.api_url');
        $token = config('notifications.whatsapp.api_token');

        if (! $url || ! $token || ! $application->phone) {
            return; // مفيش حساب WhatsApp Business API متظبط
        }

        try {
            Http::timeout(8)->withToken($token)->post($url, [
                'to' => $application->phone,
                'body' => $message,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
