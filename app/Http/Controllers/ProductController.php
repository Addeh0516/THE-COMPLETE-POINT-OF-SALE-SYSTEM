<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', ['products' => Product::latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('products.form', ['product' => new Product, 'formTitle' => 'Add a product', 'formAction' => route('products.store')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request);
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product added to the floor.');
    }

    public function edit(Product $product): View
    {
        return view('products.form', ['product' => $product, 'formTitle' => 'Edit product', 'formAction' => route('products.update', $product)]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $this->deleteStoredImage($product->image);
            $data['image'] = $this->storeImage($request);
        }
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product changes saved.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->sales()->exists()) {
            return back()->with('error', 'This product has sales history, so it cannot be deleted. Set its stock to 0 instead.');
        }

        $this->deleteStoredImage($product->image);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product removed from the floor.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        return $request->hasFile('image') ? $this->storePublicImage($request->file('image')) : null;
    }

    private function deleteStoredImage(?string $image): void
    {
        if (! $image) {
            return;
        }

        if (str_starts_with($image, 'uploads/')) {
            @unlink(public_path($image));

            return;
        }

        Storage::disk('public')->delete($image);
    }

    private function storePublicImage(UploadedFile $file): string
    {
        $directory = public_path('uploads/products');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $file->hashName();
        $file->move($directory, $filename);

        return 'uploads/products/'.$filename;
    }
}
