<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionConfig;
use Illuminate\Http\Request;

class SubscriptionConfigController extends Controller
{
    /**
     * Display subscription configurator settings
     */
    public function index()
    {
        // Use standard Eloquent all() to get all config records
        $configs = SubscriptionConfig::orderBy('key')->get();
        
        return view('admin.subscription-config.index', compact('configs'));
    }

    /**
     * Update subscription configurator settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.key' => 'required|string',
            'configs.*.value' => 'required',
        ]);

        foreach ($validated['configs'] as $configData) {
            SubscriptionConfig::set($configData['key'], $configData['value']);
        }

        return redirect()->route('admin.subscription-config.index')
            ->with('success', 'Nastavení bylo úspěšně aktualizováno.');
    }
}
