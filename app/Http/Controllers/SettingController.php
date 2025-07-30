<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }

    public function backup()
    {
        try {
            Artisan::call('backup:run');
            return back()->with('success', 'Backup created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function downloadBackup(Request $request)
    {
        $filename = $request->filename;
        
        if (Storage::exists('backups/' . $filename)) {
            return Storage::download('backups/' . $filename);
        }
        
        return back()->with('error', 'Backup file not found');
    }
}
