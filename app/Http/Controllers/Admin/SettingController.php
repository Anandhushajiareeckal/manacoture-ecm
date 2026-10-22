<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'group' => 'general',
                    'label' => ucwords(str_replace('_', ' ', $key)),
                ]);
            }
        }

        ActivityLog::log('settings.update', "Updated store settings");

        return back()->with('success', 'Settings updated successfully.');
    }
}
