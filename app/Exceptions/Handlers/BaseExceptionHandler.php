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

class BaseExceptionHandler implements ExceptionHandlerInterface
{
    public int $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    public bool $error = true;
    public mixed $message;

    public function __invoke(\Exception $exception): array
    {
        return [
            'error' => $this->isError(),
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isError(): bool
    {
        return $this->error;
    }

    public function getMessage(): mixed
    {
        return $this->message;
    }
}
