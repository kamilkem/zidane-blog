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

use App\Http\Requests\UserPasswordResetFinishRequest;
use App\Http\Requests\UserPasswordResetInitRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use function auth;
use function response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserServiceInterface $userService,
    ) {
    }

    public function index(): ResourceCollection
    {
        return UserResource::collection($this->userRepository->getPaginatedUsers());
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $this->userService->update($user, $request->validated());
            DB::commit();

            return UserResource::make($user);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function delete(User $user)
    {
        DB::beginTransaction();

        try {
            $this->userService->delete($user);
            DB::commit();

            return response(null, 204);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function register(UserRegisterRequest $request): UserResource
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());
            DB::commit();

            return UserResource::make($user);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function me(): UserResource
    {
        return UserResource::make(auth()->user());
    }

    public function initPasswordReset(UserPasswordResetInitRequest $request): Response
    {
        $validated = $request->validated();
        $user = $this->userRepository->findByEmail($validated['email']);

        if ($user) {
            DB::beginTransaction();

            try {
                $this->userService->initPasswordReset($user);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                throw $exception;
            }
        }

        return response(null, 204);
    }

    public function finishPasswordReset(UserPasswordResetFinishRequest $request): Response
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $this->userService->finishPasswordReset(
                $validated['token'],
                $validated['password'],
            );
            DB::commit();

            return response(null, 204);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
