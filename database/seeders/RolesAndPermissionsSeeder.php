<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Permissions ---
        // Booking
        Permission::create(['name' => 'manage bookings']);
        Permission::create(['name' => 'view bookings']);

        // Rooms
        Permission::create(['name' => 'manage rooms']);

        // Guests
        Permission::create(['name' => 'manage guests']);

        // Reports
        Permission::create(['name' => 'view reports']);

        // Users
        Permission::create(['name' => 'manage users']);

        // --- Roles ---
        $receptionistRole = Role::create(['name' => 'Resepsionis']);
        $receptionistRole->givePermissionTo([
            'manage bookings',
            'view bookings',
            'manage guests'
        ]);

        $ownerRole = Role::create(['name' => 'Owner']);
        // Owner gets all permissions
        $ownerRole->syncPermissions(Permission::all());

        // --- Create Users ---
        $receptionist = User::factory()->create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@hotel.com',
        ]);
        $receptionist->assignRole($receptionistRole);

        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@hotel.com',
        ]);
        $owner->assignRole($ownerRole);
    }
}
