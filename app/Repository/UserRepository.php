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
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    public function getUserByEmail(string $email): Builder
    {
        return User::where(['email' => $email]);
    }
}
