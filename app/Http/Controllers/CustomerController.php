<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customers.index', ['customers' => Customer::latest()->paginate(14)]);
    }

    public function create(): View
    {
        return view('customers.form', [
            'customer' => new Customer,
            'countryCodes' => config('countries.calling_codes'),
            'formTitle' => 'Add a customer',
            'formAction' => route('customers.store'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'Customer profile added.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.form', [
            'customer' => $customer,
            'countryCodes' => config('countries.calling_codes'),
            'formTitle' => 'Edit customer',
            'formAction' => route('customers.update', $customer),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'Customer changes saved.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->sales()->exists()) {
            return back()->with('error', 'This customer has transaction history, so the record is kept for auditability.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone_country_code' => ['nullable', 'string', Rule::in(array_keys(config('countries.calling_codes')))],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s().-]+$/'],
        ]);

        $phoneNumber = preg_replace('/\D+/', '', $data['phone_number'] ?? '');
        $data['phone'] = $phoneNumber !== '' ? ($data['phone_country_code'] ?? '+63').' '.$phoneNumber : null;

        return Arr::only($data, ['full_name', 'email', 'phone']);
    }
}
