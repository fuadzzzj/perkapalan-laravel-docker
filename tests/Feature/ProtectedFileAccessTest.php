<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProtectedFileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_files_are_downloadable_by_owner_and_teacher_only(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create(['role' => 'anggota']);
        $stranger = User::factory()->create(['role' => 'anggota']);
        $teacher = User::factory()->create(['role' => 'guru']);
        Storage::disk('local')->put('laporan/private.pdf', 'report content');
        $report = Laporan::query()->create([
            'user_id' => $owner->id,
            'judul' => 'Laporan Mesin',
            'status' => 'menunggu',
            'tanggal_kirim' => now()->toDateString(),
            'file_path' => 'laporan/private.pdf',
        ]);

        $this->actingAs($owner)->get(route('member.laporan.download', $report))->assertDownload('laporan-'.$report->id.'.pdf');
        $this->actingAs($teacher)->get(route('guru.laporan.download', $report))->assertDownload('laporan-'.$report->id.'.pdf');
        $this->actingAs($stranger)->get(route('member.laporan.download', $report))->assertForbidden();
        auth()->logout();
        $this->get(route('member.laporan.download', $report))->assertRedirect(route('login'));
    }

    public function test_chat_media_route_requires_an_authenticated_chat_member(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('chat/private.txt', 'shared attachment');
        $chat = Chat::query()->create([
            'user_id' => User::factory()->create(['role' => 'anggota'])->id,
            'pesan' => 'Lampiran',
            'media_path' => 'chat/private.txt',
            'media_type' => 'document',
        ]);

        $this->get(route('chat.media', $chat))->assertRedirect(route('login'));
        $response = $this->actingAs(User::factory()->create(['role' => 'anggota']))
            ->get(route('chat.media', $chat))
            ->assertOk();

        $this->assertSame('shared attachment', $response->streamedContent());
    }
}
