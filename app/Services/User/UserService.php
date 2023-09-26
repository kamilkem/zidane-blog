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

use App\Events\UserPasswordResetInit;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use function __;
use function base64_decode;
use function base64_encode;
use function event;
use function is_numeric;
use function json_decode;
use function json_encode;

class UserService implements UserServiceInterface
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function initPasswordReset(User $user): void
    {
        Password::sendResetLink(
            ['email' => $user->email],
            function (User $user, string $token) {
                $hashToken = $this->generateUserSignedToken($user, $token);

                event(new UserPasswordResetInit($user, $hashToken));
            }
        );
    }

    public function finishPasswordReset(string $hashToken, string $newPassword): void
    {
        $userSignedToken = $this->parseUserSignedToken($hashToken);

        $user = $this->userRepository->find($userSignedToken['user']['id']);

        Password::reset([
            'email' => $user->email,
            'token' => $userSignedToken['token'],
            'password' => $newPassword,
        ], function (User $user, string $password) {
            $hashedPassword = Hash::make($password);

            $this->userRepository->updatePassword($user, $hashedPassword);
        });
    }

    public function update(User $user, array $validated): User
    {
        $user->name = $validated['name'] ?? $user->name;
        $user->email = $validated['email'] ?? $user->email;
        // What if single existing admin will remove his role
        $validated['roles'] ?? $user->syncRoles($validated['roles']);
        $user->save();

        return $user;
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    protected function parseUserSignedToken(string $token): array
    {
        $token = json_decode(base64_decode($token), true);

        if (!$this->validateUserSignedToken($token)) {
            throw new TokenMismatchException(__('The token is invalid.'));
        }

        return $token;
    }

    protected function validateUserSignedToken(array $token): bool
    {
        if (!isset($token['user']['id']) || !is_numeric($token['user']['id']) || !isset($token['token'])) {
            return false;
        }

        return null !== $this->userRepository->find($token['user']['id']);
    }

    protected function generateUserSignedToken(User $user, string $token): string
    {
        return base64_encode(
            json_encode([
                'user' => [
                    'id' => $user->id,
                ],
                'token' => $token
            ])
        );
    }
}
