<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_the_staff_login(): void
    {
        foreach (['/dashboard', '/products', '/customers', '/staff', '/sales'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }

    public function test_staff_can_record_a_sale_and_stock_decreases(): void
    {
        $staff = User::create(['username' => 'test.operator', 'full_name' => 'Test Operator', 'password' => Hash::make('midnight123')]);
        $product = Product::create(['name' => 'Test Token Pack', 'price' => 100, 'stock_quantity' => 8]);

        $response = $this->actingAs($staff)->post('/sales', ['product_id' => $product->id, 'quantity' => 3]);

        $response->assertRedirect('/sales');
        $this->assertDatabaseHas('sales', ['product_id' => $product->id, 'quantity' => 3, 'total_price' => 300]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 5]);
    }

    public function test_sale_is_rejected_when_quantity_exceeds_stock(): void
    {
        $staff = User::create(['username' => 'test.operator', 'full_name' => 'Test Operator', 'password' => Hash::make('midnight123')]);
        $product = Product::create(['name' => 'Limited Prize', 'price' => 100, 'stock_quantity' => 2]);

        $response = $this->actingAs($staff)->from('/sales/create')->post('/sales', ['product_id' => $product->id, 'quantity' => 3]);

        $response->assertRedirect('/sales/create')->assertSessionHasErrors([
            'quantity' => 'Only 2 unit(s) of Limited Prize are available. Reduce the quantity and try again.',
        ]);
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 2]);
    }

    public function test_customer_phone_saves_the_selected_international_country_code(): void
    {
        $staff = User::create(['username' => 'test.operator', 'full_name' => 'Test Operator', 'password' => Hash::make('midnight123')]);
        $form = $this->actingAs($staff)->get('/customers/create');
        $form->assertSee('option value="+63" selected', false);

        $response = $this->actingAs($staff)->post('/customers', [
            'full_name' => 'International Player',
            'email' => 'player@example.com',
            'phone_country_code' => '+971',
            'phone_number' => '50 123 4567',
        ]);

        $response->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['email' => 'player@example.com', 'phone' => '+971 501234567']);
    }

    public function test_staff_avatar_is_saved_to_a_display_ready_public_path(): void
    {
        $staff = User::create(['username' => 'test.operator', 'full_name' => 'Test Operator', 'password' => Hash::make('midnight123')]);

        $response = $this->actingAs($staff)->post('/staff', [
            'username' => 'new.avatar',
            'full_name' => 'Avatar Operator',
            'password' => 'midnight123',
            'password_confirmation' => 'midnight123',
            'avatar' => UploadedFile::fake()->createWithContent('avatar.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
        ]);

        $response->assertRedirect('/staff');
        $createdStaff = User::where('username', 'new.avatar')->firstOrFail();
        $this->assertStringStartsWith('uploads/avatars/', $createdStaff->avatar);
        $this->assertFileExists(public_path($createdStaff->avatar));

        @unlink(public_path($createdStaff->avatar));
    }

    public function test_product_image_is_saved_to_a_display_ready_public_path(): void
    {
        $staff = User::create(['username' => 'test.operator', 'full_name' => 'Test Operator', 'password' => Hash::make('midnight123')]);

        $response = $this->actingAs($staff)->post('/products', [
            'name' => 'Uploaded Prize',
            'price' => 120,
            'stock_quantity' => 4,
            'image' => UploadedFile::fake()->createWithContent('prize.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
        ]);

        $response->assertRedirect('/products');
        $product = Product::where('name', 'Uploaded Prize')->firstOrFail();
        $this->assertStringStartsWith('uploads/products/', $product->image);
        $this->assertFileExists(public_path($product->image));

        @unlink(public_path($product->image));
    }
}
