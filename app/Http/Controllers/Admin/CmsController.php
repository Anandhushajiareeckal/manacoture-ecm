<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;

class CmsController extends Controller
{
    public function home()
    {
        $categoriesCount = Category::count();
        $productsCount = Product::count();

        return view('admin.cms.home', compact('categoriesCount', 'productsCount'));
    }

    public function about()
    {
        return view('admin.cms.about');
    }

    public function update(Request $request)
    {
        // 1. Handle file uploads
        $imageFields = [
            'cms_hero_image' => 'hero_image_file',
            'cms_bridal_image' => 'bridal_image_file',
            'cms_festival_image' => 'festival_image_file',
            'cms_about_hero_image' => 'about_hero_image_file',
            'cms_about_ethics_image' => 'about_ethics_image_file',
        ];

        foreach ($imageFields as $settingKey => $fileInputName) {
            if ($request->hasFile($fileInputName)) {
                $path = $request->file($fileInputName)->store('cms', 'public');
                Setting::updateOrCreate(
                    ['key' => $settingKey],
                    [
                        'value' => 'storage/' . $path,
                        'group' => 'cms',
                        'type' => 'string',
                        'label' => ucwords(str_replace('_', ' ', $settingKey)),
                        'is_public' => true,
                    ]
                );
            }
        }

        // 2. Handle text, boolean, and json fields
        $inputs = $request->except(array_merge(['_token', '_method'], array_values($imageFields)));

        // Handle boolean toggles that might be absent from request if unchecked
        if ($request->has('cms_form_context') && $request->input('cms_form_context') === 'home') {
            $inputs['cms_announcement_active'] = $request->has('cms_announcement_active') ? '1' : '0';
        }

        foreach ($inputs as $key => $value) {
            if ($key === 'cms_form_context') continue;

            $setting = Setting::where('key', $key)->first();
            $valToSave = is_array($value) ? json_encode($value) : (string)$value;

            if ($setting) {
                $setting->update(['value' => $valToSave]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $valToSave,
                    'group' => 'cms',
                    'type' => is_array($value) ? 'json' : 'string',
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'is_public' => true,
                ]);
            }
        }

        ActivityLog::log('cms.update', "Updated storefront CMS settings");

        return back()->with('success', 'Storefront content updated successfully! Changes are now live on the storefront.');
    }
}
