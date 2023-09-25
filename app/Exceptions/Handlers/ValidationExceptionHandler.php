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

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ValidationExceptionHandler extends BaseExceptionHandler
{
    public int $code = Response::HTTP_BAD_REQUEST;

    /**
     * @param ValidationException $exception
     */
    public function __invoke(\Exception $exception): array
    {
        return [
            'error' => $this->isError(),
            'code' => $this->getCode(),
            'message' => $exception->errors(),
        ];
    }
}
