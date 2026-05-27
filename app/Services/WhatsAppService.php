<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendRegistrasiKehadiran(string $nomor, string $namaPekurban, string $kodeRegistrasi): bool
    {
        try {
            $pesan = "Assalamu'alaikum, $namaPekurban. Bismillah... Journey Qurbannya dimulai dengan kode: $kodeRegistrasi. Sampaikan Kode Qurbanmu saat pengambilan bagian hewan kurbanmu nanti. Kalau mau diambil oleh Ojek Online atau perantara lain, jangan lupa sampaikan Kode Qurbanmu ke perantaramu.";
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Api-Key'    => config('services.whatsapp.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->post(config('services.whatsapp.url'), [
                    'sessionId'   => config('services.whatsapp.session'),
                    'chatId'      => '6282113009800',
                    'message'     => $pesan,
                    'typingTime'  => 2000,
                ]);

            $json    = $response->json();
            $success = $response->successful() && ($json['success'] ?? false);

            Log::info('WhatsApp response', [
                'nomor'      => $nomor,
                'http_status'=> $response->status(),
                'success'    => $success,
                'message'    => $json['message'] ?? null,
                'message_id' => $json['data']['messageId'] ?? null,
                'timestamp'  => $json['data']['timestamp'] ?? null,
            ]);

            return $success;
        } catch (\Throwable $e) {
            Log::error('WhatsApp exception', [
                'nomor'   => $nomor,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
