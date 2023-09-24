<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function app;
use function sprintf;

class DatabaseSeeder extends Seeder
{
    private const EMAIL_DOMAIN = 'kamildev.me';

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'manage-entries']);

        /** @var Role $adminRole */
        $adminRole = Role::create(['name' => 'Admin']);
        /** @var Role $editorRole */
        $editorRole = Role::create(['name' => 'Editor']);
        /** @var Role $userRole */
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo([
            'manage-users',
            'manage-entries',
        ]);
        $editorRole->givePermissionTo([
            'manage-entries'
        ]);

        User::factory()
            ->create([
                'name' => 'Zidane',
                'email' => sprintf('admin@%s', self::EMAIL_DOMAIN),
            ])
            ->assignRole($adminRole);

        for ($i = 0; $i < 3; $i++) {
            User::factory()
                ->create([
                    'email' => sprintf('editor_%d@%s', $i, self::EMAIL_DOMAIN)
                ])
                ->each(function (User $user) use ($editorRole) {
                    $user->assignRole($editorRole);

                    Entry::factory()
                        ->count(10)
                        ->create([
                            'user_id' => $user->id
                        ]);
                });
        }

        for ($i = 0; $i < 5; $i++) {
            User::factory()
                ->create([
                    'email' => sprintf('user_%d@%s', $i, self::EMAIL_DOMAIN)
                ])
                ->assignRole($userRole);
        }
    }
}
