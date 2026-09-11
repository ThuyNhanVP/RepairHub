<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_customers(): void
    {
        Customer::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('Quản lý khách hàng');
    }

    public function test_can_create_customer(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'Nguyễn Văn A',
            'phone' => '0901234567',
            'email' => 'nguyenvana@example.com',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'notes' => 'Khách hàng VIP',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Nguyễn Văn A',
            'phone' => '0901234567',
        ]);
    }

    public function test_can_show_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->user)->get(route('customers.show', $customer));

        $response->assertStatus(200);
        $response->assertSee($customer->name);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user)->put(route('customers.update', $customer), [
            'name' => 'Updated Name',
            'phone' => $customer->phone,
            'email' => $customer->email,
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('customers.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), []);

        $response->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_validates_unique_phone(): void
    {
        Customer::factory()->create(['phone' => '0901234567']);

        $response = $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'Test',
            'phone' => '0901234567',
        ]);

        $response->assertSessionHasErrors('phone');
    }
}
