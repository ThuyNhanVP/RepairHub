<?php

namespace Tests\Feature;

use App\Models\Part;
use App\Models\PartCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_list_parts(): void
    {
        Part::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('parts.index'));

        $response->assertOk();
        $response->assertSee('Quản lý linh kiện');
    }

    public function test_can_create_part(): void
    {
        $category = PartCategory::factory()->create();

        $response = $this->actingAs($this->user)->post(route('parts.store'), [
            'name' => 'Màn hình iPhone 14 Pro',
            'sku' => 'PRT-1001',
            'description' => 'Màn hình chính hãng',
            'category_id' => $category->id,
            'brand' => 'Apple',
            'unit' => 'cái',
            'cost_price' => 2500000,
            'sale_price' => 3200000,
            'stock_qty' => 10,
            'min_stock_qty' => 3,
            'location' => 'A-01',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('parts.index'));
        $this->assertDatabaseHas('parts', [
            'sku' => 'PRT-1001',
            'name' => 'Màn hình iPhone 14 Pro',
        ]);
    }

    public function test_can_show_part(): void
    {
        $part = Part::factory()->create();

        $response = $this->actingAs($this->user)->get(route('parts.show', $part));

        $response->assertOk();
        $response->assertSee($part->name);
    }

    public function test_can_update_part(): void
    {
        $part = Part::factory()->create([
            'name' => 'Màn hình cũ',
            'sku' => 'PRT-2001',
        ]);

        $response = $this->actingAs($this->user)->put(route('parts.update', $part), [
            'name' => 'Màn hình mới',
            'sku' => 'PRT-2001',
            'description' => 'Đã cập nhật',
            'category_id' => $part->category_id,
            'brand' => 'Samsung',
            'unit' => 'cái',
            'cost_price' => 1800000,
            'sale_price' => 2400000,
            'stock_qty' => 8,
            'min_stock_qty' => 2,
            'location' => 'B-02',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('parts.show', $part));
        $this->assertDatabaseHas('parts', [
            'id' => $part->id,
            'name' => 'Màn hình mới',
        ]);
    }

    public function test_can_delete_part(): void
    {
        $part = Part::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('parts.destroy', $part));

        $response->assertRedirect(route('parts.index'));
        $this->assertDatabaseMissing('parts', ['id' => $part->id]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('parts.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('parts.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'sku',
            'cost_price',
            'sale_price',
            'stock_qty',
            'min_stock_qty',
        ]);
    }

    public function test_validates_unique_sku(): void
    {
        Part::factory()->create(['sku' => 'PRT-3001']);

        $response = $this->actingAs($this->user)->post(route('parts.store'), [
            'name' => 'Pin Samsung A55',
            'sku' => 'PRT-3001',
            'cost_price' => 1500000,
            'sale_price' => 2200000,
            'stock_qty' => 5,
            'min_stock_qty' => 2,
        ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_can_add_stock(): void
    {
        $part = Part::factory()->create(['stock_qty' => 10]);

        $response = $this->actingAs($this->user)->post(route('parts.add-stock', $part), [
            'qty' => 5,
            'unit_cost' => 250000,
            'reference_number' => 'PO-001',
            'notes' => 'Nhập thêm linh kiện',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('parts', ['id' => $part->id, 'stock_qty' => 15]);
        $this->assertDatabaseHas('stock_movements', [
            'part_id' => $part->id,
            'type' => 'in',
            'qty' => 5,
        ]);
    }

    public function test_can_remove_stock(): void
    {
        $part = Part::factory()->create(['stock_qty' => 10]);

        $response = $this->actingAs($this->user)->post(route('parts.remove-stock', $part), [
            'qty' => 3,
            'reference_number' => 'OUT-001',
            'notes' => 'Xuất cho sửa chữa',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('parts', ['id' => $part->id, 'stock_qty' => 7]);
        $this->assertDatabaseHas('stock_movements', [
            'part_id' => $part->id,
            'type' => 'out',
            'qty' => 3,
        ]);
    }

    public function test_can_adjust_stock(): void
    {
        $part = Part::factory()->create(['stock_qty' => 10]);

        $response = $this->actingAs($this->user)->post(route('parts.adjust-stock', $part), [
            'qty' => 20,
            'notes' => 'Điều chỉnh sai số lượng',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('parts', ['id' => $part->id, 'stock_qty' => 20]);
        $this->assertDatabaseHas('stock_movements', [
            'part_id' => $part->id,
            'type' => 'adjustment',
            'qty' => 20,
        ]);
    }

    public function test_low_stock_filter_lists_only_low_stock_items(): void
    {
        $lowStockPart = Part::factory()->create([
            'stock_qty' => 2,
            'min_stock_qty' => 5,
            'name' => 'Pin dự phòng thấp',
        ]);
        $normalPart = Part::factory()->create([
            'stock_qty' => 20,
            'min_stock_qty' => 5,
            'name' => 'Pin dự phòng bình thường',
        ]);

        $response = $this->actingAs($this->user)->get(route('parts.index', ['low_stock' => '1']));

        $response->assertOk();
        $response->assertSee($lowStockPart->name);
        $response->assertDontSee($normalPart->name);
    }
}
