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

namespace App\Listeners;

use App\Events\UserRegistrationInit;
use App\Models\RoleEnum;
use Spatie\Permission\Models\Role;

class GrantUserDefaultRole
{
    public function handle(UserRegistrationInit $event): void
    {
        $user = $event->user;
        $defaultRole = Role::findByName(RoleEnum::getDefaultRole()->value);

        $user->syncRoles($defaultRole);
    }
}
