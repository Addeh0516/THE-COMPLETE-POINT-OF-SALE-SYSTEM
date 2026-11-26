<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        return view('sales.index', ['sales' => Sale::with(['product', 'customer', 'staff'])->latest()->paginate(16)]);
    }

    public function create(): View
    {
        return view('sales.create', [
            'products' => Product::where('stock_quantity', '>', 0)->orderBy('name')->get(),
            'customers' => Customer::orderBy('full_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999999'],
        ]);

        try {
            DB::transaction(function () use ($data, $request): void {
                $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);

                if ($data['quantity'] > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => "Only {$product->stock_quantity} unit(s) of {$product->name} are available. Reduce the quantity and try again.",
                    ]);
                }

                $product->decrement('stock_quantity', $data['quantity']);
                Sale::create([
                    'product_id' => $product->id,
                    'customer_id' => $data['customer_id'] ?? null,
                    'sold_by' => $request->user()->id,
                    'quantity' => $data['quantity'],
                    'total_price' => round((float) $product->price * (int) $data['quantity'], 2),
                ]);
            });
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        }

        return redirect()->route('sales.index')->with('success', 'Sale recorded. Stock has been updated.');
    }
}
