<?php

/**
 * This file is part of the zidane-blog package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Entry;
use App\Models\RoleEnum;
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
        $adminRole = Role::create(['name' => RoleEnum::ROLE_ADMIN->value]);
        /** @var Role $editorRole */
        $editorRole = Role::create(['name' => RoleEnum::ROLE_EDITOR->value]);
        /** @var Role $userRole */
        $userRole = Role::create(['name' => RoleEnum::ROLE_USER->value]);

        $adminRole->givePermissionTo([
            'manage-users',
            'manage-entries',
        ]);
        $editorRole->givePermissionTo([
            'manage-entries'
        ]);

        if (app()->environment('local')) {
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
}
