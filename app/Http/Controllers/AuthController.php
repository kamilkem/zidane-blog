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

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\TokenResource;
use App\Services\Auth\AuthServiceInterface;

class AuthController extends Controller
{
    public function __construct(protected AuthServiceInterface $authService)
    {
    }

    public function login(AuthLoginRequest $request): TokenResource
    {
        $token = $this->authService->login($request->validated());

        return TokenResource::make($token);
    }
}
