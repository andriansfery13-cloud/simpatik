<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function integrations()
    {
        // Only allow Kabupaten users to access settings
        if (!auth()->user()->isKabupaten()) {
            abort(403, 'Unauthorized access.');
        }

        $openaiKey = Setting::get('openai_api_key');
        
        return view('settings.integrations', compact('openaiKey'));
    }

    public function updateIntegrations(Request $request)
    {
        if (!auth()->user()->isKabupaten()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'openai_api_key' => 'nullable|string',
        ]);

        Setting::set('openai_api_key', $request->openai_api_key);

        return redirect()->route('settings.integrations')->with('success', 'Pengaturan integrasi berhasil disimpan.');
    }
}
