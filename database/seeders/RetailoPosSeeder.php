<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RetailoPosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $group = \App\Models\CustomerGroup::create(['name' => 'General', 'percentage' => 0]);

    \App\Models\Customer::create([
        'customer_group_id' => $group->id,
        'name' => 'Walk-in Customer',
        'phone_number' => '0000000000',
        'address' => 'N/A',
    ]);

    \App\Models\Account::create([
        'account_no' => '1',
        'name' => 'Cash',
        'is_default' => true,
        'is_active' => true,
    ]);

    $role = \App\Models\Role::create(['name' => 'Admin', 'is_active' => true]);
    $warehouse = \App\Models\Warehouse::create(['name' => 'Main Warehouse', 'address' => 'N/A']);

    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'phone' => '0000000000',
        'role_id' => $role->id,
        'warehouse_id' => $warehouse->id,
        'is_active' => true,
        'is_deleted' => false,
    ]);
}

}
