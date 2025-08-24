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


        $folder = 'Mobile Shop Management System';
        $files = Storage::files($folder);

        $backupFiles = [];
        foreach ($files as $file) {
            $backupFiles[] = [
                'name' => basename($file),
                'size' => $this->formatSize(Storage::size($file)),
                'last_modified' => date('Y-m-d H:i:s', Storage::lastModified($file)),
            ];
        }

        // ফাইলগুলো নামের উল্টা ক্রমে সাজানো (নতুন ফাইল প্রথমে দেখাবে)
        $backupFiles = array_reverse($backupFiles);
        return view('settings.index', compact('settings','backupFiles'));
    }

    // ফাইল সাইজ ফরম্যাট করার মেথড যোগ করুন
    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }


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

    public function downloadBackup($filename)
    {
        try {
            // ফাইলের পাথ
            $filePath = 'Mobile Shop Management System/' . urldecode($filename);


            if (!Storage::exists($filePath)) {
                return back()->with('error', 'ফাইলটি পাওয়া যায়নি: ' . $filename);
            }

            // ফাইলের পূর্ণ পাথ
            $fullPath = Storage::path($filePath);

            // ফাইলের নাম পরিষ্কার করুন (শুধুমাত্র ফাইলের নাম অংশ নিন)
            $cleanFilename = basename($filename);

            // ডাউনলোড রেস্পন্স
            return response()->download($fullPath, $cleanFilename, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $cleanFilename . '"',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'ডাউনলোড ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function deleteBackup($file)
    {

        $filePath = 'Mobile Shop Management System/' . $file;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return back()->with('success', 'Backup deleted successfully');
        }

        return back()->with('error', 'ফাইলটি ডিলিট করা যায়নি');

    }
}
