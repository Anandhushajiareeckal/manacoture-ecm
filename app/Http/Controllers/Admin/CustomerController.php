<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\ActivityLog;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('orders');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', $request->boolean('status'));
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['addresses', 'orders.items', 'reviews.product']);
        return view('admin.customers.show', compact('customer'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:25',
            'password' => 'nullable|string|min:6',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'address_line1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:2',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $customer = Customer::create($validated);

        if (!empty($validated['address_line1'])) {
            CustomerAddress::create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
                'full_name' => $customer->full_name,
                'address_line1' => $validated['address_line1'],
                'city' => $validated['city'] ?? 'City',
                'state' => $validated['state'] ?? 'State',
                'postal_code' => $validated['postal_code'] ?? '00000',
                'country' => $validated['country'] ?? 'US',
                'phone' => $customer->phone,
                'is_default' => true,
            ]);
        }

        ActivityLog::log('customer.create', "Registered customer {$customer->full_name} ({$customer->email})", $customer);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => "required|email|max:255|unique:customers,email,{$customer->id}",
            'phone' => 'nullable|string|max:25',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $customer->update($validated);

        ActivityLog::log('customer.update', "Updated customer profile {$customer->full_name}", $customer);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer profile updated.');
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        $status = $customer->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Customer {$customer->full_name} has been {$status}.");
    }
}
