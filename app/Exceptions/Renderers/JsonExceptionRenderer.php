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

namespace App\Exceptions\Renderers;

use App\Exceptions\Handlers\BaseExceptionHandler;
use App\Exceptions\Handlers\ValidationExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use function app;

class JsonExceptionRenderer
{
    public array $handlerCasts = [
        ValidationException::class => ValidationExceptionHandler::class,
    ];

    public mixed $response;

    public function render(\Exception $exception, Request $request): JsonResponse
    {
        $this->generateResponseFromException($exception);

        if (app()->environment('local')) {
            $this->response['message'] = $this->response['message'] ?? $exception->getMessage();
            $this->response['trace'] = $exception->getTrace();
        }

        return response()->json($this->response, $this->response['code']);
    }

    protected function generateResponseFromException(\Exception $exception): void
    {
        foreach ($this->handlerCasts as $class => $handlerCast) {
            if (!$exception instanceof $class) {
                continue;
            }

            $this->response = (new $handlerCast())($exception);

            break;
        }

        $this->response = $this->response ?? (new BaseExceptionHandler())($exception);
    }
}
