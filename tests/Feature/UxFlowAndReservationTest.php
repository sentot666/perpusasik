<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Book;

class UxFlowAndReservationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_dashboard_shows_online_reservation_counter()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found.');
        }

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Reservasi Online');
    }

    public function test_settings_page_does_not_contain_carousel_tab()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found.');
        }

        $response = $this->actingAs($admin)->get(route('settings.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Foto Banner (Carousel)');
        $response->assertDontSee('tab = \'carousel\'');
    }

    public function test_sidebar_does_not_have_redundant_tambah_anggota()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found.');
        }

        $response = $this->actingAs($admin)->get(route('members.index'));
        $response->assertStatus(200);
        // Ensure sidebar has Anggota link but not redundant Tambah Anggota link
        $response->assertSee(route('members.index'));
        $response->assertDontSee(route('members.create') . '" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs');
    }

    public function test_custom_404_view_renders_cleanly()
    {
        $response = $this->get('/non-existent-page-testing-404-ux');
        $response->assertStatus(404);
        $response->assertSee(__('Halaman Tidak Ditemukan'));
        $response->assertSee(__('Beranda Perpustakaan'));
    }
}
