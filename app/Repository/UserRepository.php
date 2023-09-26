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

namespace App\Repository;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where(['email' => $email])->first();
    }

    public function getPaginatedUsers(int $itemsPerPage = 10): Paginator
    {
        return User::paginate($itemsPerPage);
    }

    public function updatePassword(User $user, string $hashedPassword): void
    {
        $user->forceFill([
            'password' => $hashedPassword,
        ])->save();
    }
}
