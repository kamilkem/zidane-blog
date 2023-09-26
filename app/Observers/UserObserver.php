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

namespace App\Observers;

use App\Events\UserRegistrationInit;
use App\Models\User;

use function event;

class UserObserver
{
    public function creating(User $user): void
    {
        event(new UserRegistrationInit($user));
    }
}
