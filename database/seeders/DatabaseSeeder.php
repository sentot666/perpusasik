<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Permissions ────────────────────────────────────────────────────────
        $permissions = [
            // Super Admin
            'manage-users', 'manage-roles', 'manage-settings', 'backup-db', 'restore-db', 'view-activity-logs', 'view-full-dashboard',
            // Pustakawan / Admin
            'view-books', 'create-books', 'edit-books', 'delete-books',
            'manage-catalog',
            'view-members', 'create-members', 'edit-members', 'delete-members',
            'print-barcode', 'print-book-label', 'print-member-card',
            'import-excel', 'export-excel',
            'process-loans', 'process-returns', 'process-renewals', 'process-fines',
            'view-reports',
            // Anggota
            'search-books', 'view-opac', 'view-stock', 'make-reservations',
            'renew-loans', 'view-history', 'download-ebook', 'change-password', 'edit-profile'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Roles ──────────────────────────────────────────────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $pustakawan = Role::firstOrCreate(['name' => 'pustakawan']);
        $anggota    = Role::firstOrCreate(['name' => 'anggota']);

        // Super Admin: all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Pustakawan: daily operations & catalogue management
        $pustakawan->syncPermissions([
            'view-books', 'create-books', 'edit-books', 'delete-books',
            'manage-catalog',
            'view-members', 'create-members', 'edit-members', 'delete-members',
            'print-barcode', 'print-book-label', 'print-member-card',
            'import-excel', 'export-excel',
            'process-loans', 'process-returns', 'process-renewals', 'process-fines',
            'view-reports'
        ]);

        // Anggota: self-service & public catalog access
        $anggota->syncPermissions([
            'search-books', 'view-opac', 'view-stock', 'make-reservations',
            'renew-loans', 'view-history', 'download-ebook', 'change-password', 'edit-profile'
        ]);

        // ── Default Users ──────────────────────────────────────────────────────
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@makarya.local',
                'password' => bcrypt('admin123'),
                'is_active'=> true,
            ]
        );
        $adminUser->assignRole('super-admin');

        $pustakawanUser = User::firstOrCreate(
            ['username' => 'pustakawan'],
            [
                'name'     => 'Pustakawan Perpustakaan',
                'email'    => 'pustakawan@makarya.local',
                'password' => bcrypt('pustakawan123'),
                'is_active'=> true,
            ]
        );
        $pustakawanUser->assignRole('pustakawan');

        $anggotaMember = \App\Models\Member::firstOrCreate(
            ['member_code' => 'anggota'],
            [
                'name'         => 'Anggota Perpustakaan',
                'email'        => 'anggota@makarya.local',
                'phone'        => '08123456789',
                'gender'       => 'L',
                'address'      => 'Jl. Perpustakaan No. 1',
                'member_type'  => 'Umum',
                'register_date'=> today(),
                'expired_date' => today()->addYears(5),
                'is_active'    => true,
                'barcode'      => 'Manggota',
            ]
        );

        $anggotaUser = User::firstOrCreate(
            ['username' => 'anggota'],
            [
                'name'      => 'Anggota Perpustakaan',
                'email'     => 'anggota@makarya.local',
                'password'  => bcrypt('anggota123'),
                'is_active' => true,
                'member_id' => $anggotaMember->id,
            ]
        );
        $anggotaUser->assignRole('anggota');

        // ── Default Library Settings ───────────────────────────────────────────
        $defaultSettings = [
            ['key' => 'library_name',   'value' => 'Perpustakaan Makarya', 'group' => 'general', 'label' => 'Nama Perpustakaan'],
            ['key' => 'library_address','value' => '',  'group' => 'general', 'label' => 'Alamat'],
            ['key' => 'library_phone',  'value' => '',  'group' => 'general', 'label' => 'Telepon'],
            ['key' => 'library_email',  'value' => '',  'group' => 'general', 'label' => 'Email'],
            ['key' => 'loan_duration',  'value' => '14','group' => 'circulation', 'label' => 'Durasi Pinjam (hari)', 'type' => 'number'],
            ['key' => 'max_loan_items', 'value' => '3', 'group' => 'circulation', 'label' => 'Maks Pinjam (eksemplar)', 'type' => 'number'],
            ['key' => 'max_renewals',   'value' => '2', 'group' => 'circulation', 'label' => 'Maks Perpanjang', 'type' => 'number'],
            ['key' => 'fine_per_day',   'value' => '1000','group' => 'circulation', 'label' => 'Denda per Hari (Rp)', 'type' => 'number'],
            ['key' => 'member_expiry_years','value' => '1','group' => 'membership', 'label' => 'Masa Berlaku Anggota (tahun)', 'type' => 'number'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('✅ Seeder selesai: Roles, Permissions, Default users, dan Settings sudah dibuat.');
        $this->command->info('   Super Admin: admin / admin123');
        $this->command->info('   Pustakawan:  pustakawan / pustakawan123');
        $this->command->info('   Anggota:     anggota / anggota123');
    }
}
