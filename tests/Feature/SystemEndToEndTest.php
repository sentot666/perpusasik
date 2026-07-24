<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\BookItem;
use App\Models\Circulation;

class SystemEndToEndTest extends TestCase
{
    use DatabaseTransactions;

    public function test_opac_page_loads()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_guestbook_page_loads()
    {
        $response = $this->get('/isi-buku-tamu');
        $response->assertStatus(200);
    }
    
    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    public function test_admin_can_login_and_access_dashboard()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found in DB.');
        }

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Perpustakaan Santo Paulus'); 
    }
    
    public function test_admin_can_access_books_module()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found in DB.');
        }

        $response = $this->actingAs($admin)->get('/books');
        $response->assertStatus(200);
    }
    
    public function test_admin_can_access_members_module()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found in DB.');
        }

        $response = $this->actingAs($admin)->get('/members');
        $response->assertStatus(200);
    }
    
    public function test_admin_can_access_circulation_module()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found in DB.');
        }

        $response = $this->actingAs($admin)->get('/sirkulasi/pinjam');
        $response->assertStatus(200);
    }

    public function test_circulation_flow()
    {
        $admin = User::where('username', 'admin')->first();
        $member = Member::where('is_active', true)->first();
        $bookItem = BookItem::where('status', 'Tersedia')
            ->whereDoesntHave('circulations', function ($query) {
                $query->where('status', 'Dipinjam');
            })->first();
        
        if (!$admin || !$member || !$bookItem) {
            $this->markTestSkipped('Required data for circulation test not found.');
        }

        $this->actingAs($admin);
        
        // Peminjaman
        $response = $this->post('/sirkulasi/pinjam', [
            'member_id' => $member->id,
            'book_item_ids' => [$bookItem->id],
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('circulations', [
            'member_id' => $member->id,
            'status' => 'Dipinjam'
        ]);
        
        $this->assertDatabaseHas('book_items', [
            'id' => $bookItem->id,
            'status' => 'Dipinjam'
        ]);
        
        // Pengembalian
        $circulation = Circulation::where('member_id', $member->id)->where('status', 'Dipinjam')->latest()->first();

        $response = $this->post('/sirkulasi/kembali', [
            'barcode' => $bookItem->barcode,
            'condition' => 'Baik',
            'fine_amount' => 0,
            'notes' => 'Test return',
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('book_items', [
            'id' => $bookItem->id,
            'status' => 'Tersedia'
        ]);

        $this->assertDatabaseHas('circulations', [
            'id' => $circulation->id,
            'status' => 'Dikembalikan'
        ]);
    }
}
