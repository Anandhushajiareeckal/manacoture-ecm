<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\ActivityLog;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('redemptions')->latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($validated);
        ActivityLog::log('coupon.create', "Created coupon {$coupon->code}", $coupon);

        return back()->with('success', "Coupon '{$coupon->code}' created successfully.");
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => "required|string|max:50|unique:coupons,code,{$coupon->id}",
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        $coupon->update($validated);
        ActivityLog::log('coupon.update', "Updated coupon {$coupon->code}", $coupon);

        return back()->with('success', "Coupon '{$coupon->code}' updated successfully.");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        ActivityLog::log('coupon.delete', "Deleted coupon {$coupon->code}", $coupon);

        return back()->with('success', 'Coupon deleted.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        $status = $coupon->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Coupon {$coupon->code} has been {$status}.");
    }
}
