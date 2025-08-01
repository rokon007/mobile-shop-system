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

    // public function update(Request $request)
    // {
    //     foreach ($request->except('_token', '_method') as $key => $value) {
    //         SystemSetting::updateOrCreate(
    //             ['key' => $key],
    //             ['value' => $value]
    //         );
    //     }

    //     return back()->with('success', 'Settings updated successfully');
    // }



    public function update(Request $request)
    {
        // Handle file uploads first
        if ($request->hasFile('company_logo')) {
           // dd('ok');
            $this->handleLogoUpload($request->file('company_logo'));
        }

        // Update other settings
        foreach ($request->except('_token', '_method', 'shop_logo') as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }

    protected function handleLogoUpload($file)
    {
        // Delete old logo if exists
        $oldLogo = SystemSetting::where('key', 'company_logo')->first();
        if ($oldLogo && $oldLogo->value && Storage::disk('public')->exists($oldLogo->value)) {
            Storage::disk('public')->delete($oldLogo->value);
        }

        // Store new logo
        $path = $file->store('logos', 'public');

        // Update database record
        SystemSetting::updateOrCreate(
            ['key' => 'shop_logo'],
            ['value' => $path, 'type' => 'file', 'group' => 'shop']
        );
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

    public function deleteBackup($file)
    {
        Storage::delete('backups/' . $file);
        return response()->json(['message' => 'Backup deleted successfully']);
    }
}
