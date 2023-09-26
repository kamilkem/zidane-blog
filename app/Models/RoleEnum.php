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

namespace App\Models;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'Admin';
    case ROLE_EDITOR = 'Editor';
    case ROLE_USER = 'User';

    public static function getDefaultRole(): self
    {
        return self::ROLE_USER;
    }
}
