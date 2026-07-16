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
            // Katalogisasi
            'view-books', 'create-books', 'edit-books', 'delete-books',
            'view-members', 'create-members', 'edit-members', 'delete-members',
            // Sirkulasi
            'process-loans', 'process-returns', 'process-renewals', 'process-fines',
            // Laporan
            'view-reports', 'export-reports',
            // Admin
            'manage-users', 'manage-settings', 'manage-roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Roles ──────────────────────────────────────────────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $librarian  = Role::firstOrCreate(['name' => 'librarian']);
        $staff      = Role::firstOrCreate(['name' => 'staff']);

        // Super Admin: all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin: all except manage-roles
        $admin->syncPermissions(
            Permission::whereNotIn('name', ['manage-roles'])->get()
        );

        // Librarian: catalogue + circulation + reports
        $librarian->syncPermissions([
            'view-books', 'create-books', 'edit-books',
            'view-members', 'create-members', 'edit-members',
            'process-loans', 'process-returns', 'process-renewals', 'process-fines',
            'view-reports',
        ]);

        // Staff: circulation only
        $staff->syncPermissions([
            'view-books', 'view-members',
            'process-loans', 'process-returns', 'process-renewals', 'process-fines',
        ]);

        // ── Default Admin User ─────────────────────────────────────────────────
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@inlislite.local',
                'password' => bcrypt('admin123'),
                'is_active'=> true,
            ]
        );
        $adminUser->assignRole('super-admin');

        // ── Default Library Settings ───────────────────────────────────────────
        $defaultSettings = [
            ['key' => 'library_name',   'value' => 'Perpustakaan INLIS Lite 3', 'group' => 'general', 'label' => 'Nama Perpustakaan'],
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

        $this->command->info('✅ Seeder selesai: Roles, Permissions, Admin user, dan Settings sudah dibuat.');
        $this->command->info('   Login: admin / admin123');
    }
}
