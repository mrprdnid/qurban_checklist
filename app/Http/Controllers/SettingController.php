<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $waEnabled = WhatsAppService::isEnabled();
        return view('settings.index', compact('waEnabled'));
    }

    public function toggleWa(Request $request)
    {
        $current = WhatsAppService::isEnabled();
        Setting::set('wa_enabled', $current ? '0' : '1');

        $label = !$current ? 'diaktifkan' : 'dimatikan';
        return back()->with('success', "WhatsApp API berhasil $label.");
    }
}
