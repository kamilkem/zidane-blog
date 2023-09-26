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

namespace App\Services\User;

use App\Models\User;

interface UserServiceInterface
{
    public function initPasswordReset(User $user): void;

    public function finishPasswordReset(string $hashToken, string $newPassword): void;

    public function update(User $user, array $validated): User;
}
