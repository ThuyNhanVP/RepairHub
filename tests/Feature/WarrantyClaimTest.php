<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warranty;
use App\Models\WarrantyClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarrantyClaimTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_create_claim_for_active_warranty(): void
    {
        $warranty = Warranty::factory()->create([
            'status' => 'active',
            'start_date' => '2026-09-01',
            'end_date' => '2027-09-01',
        ]);

        $response = $this->actingAs($this->user)->post(route('warranty-claims.store'), [
            'warranty_id' => $warranty->id,
            'issue' => 'Thiết bị không lên nguồn.',
            'notes' => 'Khách mang đủ phụ kiện.',
        ]);

        $claim = WarrantyClaim::query()->first();

        $response->assertRedirect(route('warranty-claims.show', $claim));
        $this->assertNotNull($claim);
        $this->assertSame($this->user->id, $claim->user_id);
        $this->assertSame('received', $claim->status);
        $this->assertDatabaseHas('warranty_claims', [
            'warranty_id' => $warranty->id,
            'issue' => 'Thiết bị không lên nguồn.',
        ]);
    }

    public function test_cannot_create_claim_for_expired_warranty(): void
    {
        $warranty = Warranty::factory()->create([
            'status' => 'active',
            'end_date' => '2026-01-01',
        ]);

        $response = $this->actingAs($this->user)->post(route('warranty-claims.store'), [
            'warranty_id' => $warranty->id,
            'issue' => 'Thiết bị lỗi.',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('warranty_claims', 0);
    }

    public function test_can_update_claim_status_and_resolution(): void
    {
        $claim = WarrantyClaim::factory()->create();

        $response = $this->actingAs($this->user)->put(route('warranty-claims.update', $claim), [
            'claim_code' => $claim->claim_code,
            'status' => 'completed',
            'issue' => $claim->issue,
            'resolution' => 'Đã thay linh kiện lỗi.',
        ]);

        $response->assertRedirect(route('warranty-claims.show', $claim));
        $claim->refresh();
        $this->assertSame('completed', $claim->status);
        $this->assertSame('Đã thay linh kiện lỗi.', $claim->resolution);
        $this->assertNotNull($claim->resolved_at);
    }

    public function test_can_search_claims_by_claim_code(): void
    {
        $claim = WarrantyClaim::factory()->create([
            'claim_code' => 'CL-SEARCH-001',
        ]);

        $response = $this->actingAs($this->user)->get(route('warranty-claims.index', [
            'search' => 'CL-SEARCH-001',
        ]));

        $response->assertSee('CL-SEARCH-001');
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('warranty-claims.index'));

        $response->assertRedirect(route('login'));
    }
}
