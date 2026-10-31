<?php

namespace Tests\Feature\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_pending_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pending = User::factory()->create([
            'role' => 'anggota',
            'membership_status' => 'pending',
            'approved_at' => null,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.members.approve', $pending));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $pending->id,
            'membership_status' => 'approved',
            'approved_by' => $admin->id,
        ]);
    }

    public function test_member_cannot_approve_pending_member(): void
    {
        $member = User::factory()->create(['role' => 'anggota']);
        $pending = User::factory()->create([
            'role' => 'anggota',
            'membership_status' => 'pending',
        ]);

        $response = $this->actingAs($member)->patch(route('admin.members.approve', $pending));

        $response->assertForbidden();
    }
}
