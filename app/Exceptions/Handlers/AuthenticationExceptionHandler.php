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

namespace App\Exceptions\Handlers;

use Symfony\Component\HttpFoundation\Response;

class AuthenticationExceptionHandler extends BaseExceptionHandler
{
    public int $code = Response::HTTP_UNAUTHORIZED;
}
