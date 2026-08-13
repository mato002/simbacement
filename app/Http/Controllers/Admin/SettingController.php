<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'company' => Setting::group('company'),
            'social' => Setting::group('social'),
            'seo' => Setting::group('seo'),
            'stats' => Setting::group('stats'),
            'site' => Setting::group('site'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company' => ['nullable', 'array'],
            'company.*' => ['nullable', 'string', 'max:500'],
            'social' => ['nullable', 'array'],
            'social.*' => ['nullable', 'string', 'max:500'],
            'seo' => ['nullable', 'array'],
            'seo.*' => ['nullable', 'string', 'max:500'],
            'stats' => ['nullable', 'array'],
            'stats.*' => ['nullable', 'string', 'max:40'],
            'site' => ['nullable', 'array'],
            'site.positioning' => ['nullable', 'in:official_manufacturer,authorized_distributor'],
            'site.commerce_mode' => ['nullable', 'in:quotes_only,ecommerce'],
        ]);

        foreach (['company', 'social', 'seo', 'stats', 'site'] as $group) {
            foreach ($data[$group] ?? [] as $key => $value) {
                Setting::setValue($key, $value ?: null, $group);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
