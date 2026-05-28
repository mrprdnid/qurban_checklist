<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function buildPesan(string $namaPekurban, string $kodeRegistrasi): string
    {
        $journeyUrl = route('public.journey.show', $kodeRegistrasi);
        return "Assalamu'alaikum, $namaPekurban.\nBismillah... Journey Qurbannya dimulai.\n\nKode Qurbanmu: $kodeRegistrasi.\n\nPantau perjalanan qurbanmu di sini:\n$journeyUrl\n\nSampaikan Kode Qurbanmu saat pengambilan bagian hewan kurbanmu nanti. Kalau mau diambil oleh Ojek Online atau perantara lain, jangan lupa sampaikan Kode Qurbanmu ke perantaramu.\n\nJazakumullahu khairan katsiran.\n\n.:: Panitia Qurban KAF Pusat Depok 1447H ::.";
    }

    public function sendRegistrasiKehadiran(string $nomor, string $namaPekurban, string $kodeRegistrasi): bool
    {
        try {
            $pesan = self::buildPesan($namaPekurban, $kodeRegistrasi);
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Api-Key'    => config('services.whatsapp.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->post(config('services.whatsapp.url'), [
                    'sessionId'   => config('services.whatsapp.session'),
                    'chatId'      => $nomor,
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
