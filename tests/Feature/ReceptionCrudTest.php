<?php

namespace Tests\Feature;

use App\Models\Reception;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceptionCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_receptions(): void
    {
        Reception::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('receptions.index'));

        $response->assertStatus(200);
        $response->assertSee('Phiếu tiếp nhận thiết bị');
    }

    public function test_can_create_reception(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->user)->post(route('receptions.store'), [
            'customer_id' => $customer->id,
            'brand' => 'Apple',
            'model' => 'iPhone 15 Pro',
            'serial_number' => 'ABC123456789',
            'imei' => '123456789012345',
            'device_type' => 'phone',
            'color' => 'Titan tự nhiên',
            'device_notes' => 'Màn hình có vết xước nhẹ',
            'description' => 'Khách hàng than màn hình không hiển thị, có vệt đen dọc theo màn hình',
            'notes' => 'Cần kiểm tra màn hình và mainboard',
        ]);

        $response->assertRedirect(route('receptions.show', 1));
        $this->assertDatabaseHas('receptions', [
            'customer_id' => $customer->id,
            'status' => 'received',
        ]);
        $this->assertDatabaseHas('devices', [
            'customer_id' => $customer->id,
            'brand' => 'Apple',
            'model' => 'iPhone 15 Pro',
        ]);
    }

    public function test_can_show_reception(): void
    {
        $reception = Reception::factory()->create();

        $response = $this->actingAs($this->user)->get(route('receptions.show', $reception));

        $response->assertStatus(200);
        $response->assertSee($reception->id);
        $response->assertSee($reception->customer->name);
    }

    public function test_can_update_reception(): void
    {
        $reception = Reception::factory()->create(['status' => 'received']);

        $response = $this->actingAs($this->user)->put(route('receptions.update', $reception), [
            'status' => 'diagnosing',
            'diagnosis' => 'Màn hình OLED bị vỡ, cần thay thế',
            'estimated_cost' => 2500000,
            'estimated_completion_at' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('receptions.show', $reception));
        $this->assertDatabaseHas('receptions', [
            'id' => $reception->id,
            'status' => 'diagnosing',
            'diagnosis' => 'Màn hình OLED bị vỡ, cần thay thế',
        ]);
    }

    public function test_can_delete_reception(): void
    {
        $reception = Reception::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('receptions.destroy', $reception));

        $response->assertRedirect(route('receptions.index'));
        $this->assertDatabaseMissing('receptions', ['id' => $reception->id]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('receptions.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('receptions.store'), []);

        $response->assertSessionHasErrors(['customer_id', 'description']);
    }

    public function test_creates_device_when_creating_reception(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->user)->post(route('receptions.store'), [
            'customer_id' => $customer->id,
            'brand' => 'Samsung',
            'model' => 'Galaxy S24',
            'serial_number' => 'SAM123456789',
            'imei' => '987654321012345',
            'device_type' => 'phone',
            'color' => 'Đen',
            'description' => 'Không sạc được',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('devices', [
            'customer_id' => $customer->id,
            'brand' => 'Samsung',
            'model' => 'Galaxy S24',
        ]);
        $this->assertDatabaseHas('receptions', [
            'customer_id' => $customer->id,
            'status' => 'received',
        ]);
    }
}
