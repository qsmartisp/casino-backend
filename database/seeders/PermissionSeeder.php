<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Services\Local\Repositories\WalletRepository;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param WalletRepository $walletRepository
     *
     * @return void
     *
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function run(WalletRepository $walletRepository): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // create permissions
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'disable user']);
        Permission::create(['name' => 'enable user']);
        Permission::create(['name' => 'remove_self_exclusion user']);
        Permission::create(['name' => 'verify user']);

        // create roles and assign existing permissions
        /** @var Role $role */
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('add user');
        $role->givePermissionTo('delete user');
        $role->givePermissionTo('update user');
        $role->givePermissionTo('disable user');
        $role->givePermissionTo('enable user');
        $role->givePermissionTo('remove_self_exclusion user');
        $role->givePermissionTo('verify user');

        // create a admin user
        $email = 'admin@admin.admin';
        $password = '12345678';

        /** @var User $user */
        $user = User::query()->firstOrCreate(['email' => $email], [
            'email' => $email,
            'password' => Hash::make($password),
            'remember_token' => Str::random(10),
            'nickname' => 'Admin',
            'country_id' => 1, // US
            'default_currency_id' => 1, // USD
            'subscription_by_email' => false,
        ]);

        if (!$user->hasWallet('USD')) {
            $wallet = $walletRepository->createWallet($user, $user->currency);

            $wallet->depositFloat(100);
        }

        $user->assignRole($role);
    }
}
