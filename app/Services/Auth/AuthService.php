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

namespace App\Services\Auth;

use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use function __;

class AuthService implements AuthServiceInterface
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function login(array $credentials): NewAccessToken
    {
        /** @var User $user */
        $user = $this->userRepository->getUserByEmail($credentials['email'])->first();

        if (!$user || !$user->checkPassword($credentials['password'])) {
            throw new UnauthorizedHttpException('api', __('Invalid credentials provided.'));
        }

        return $this->generateTokenForUser($user);
    }

    protected function generateTokenForUser(User $user): NewAccessToken
    {
        return $user->createToken(
            Str::random(32)
        );
    }
}
