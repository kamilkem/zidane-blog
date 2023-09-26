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

use App\Http\Requests\EntryCreateRequest;
use App\Http\Requests\EntryUpdateRequest;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Repository\EntryRepositoryInterface;
use App\Services\Entry\EntryServiceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use function response;

class EntryController
{
    public function __construct(
        protected EntryRepositoryInterface $entryRepository,
        protected EntryServiceInterface $entryService
    ) {
    }

    public function index(): ResourceCollection
    {
        return EntryResource::collection($this->entryRepository->getPaginatedEntries());
    }

    public function show(Entry $entry): EntryResource
    {
        return EntryResource::make($entry);
    }

    public function store(EntryCreateRequest $request): EntryResource
    {
        DB::beginTransaction();

        try {
            $entry = $this->entryService->store($request->validated());
            DB::commit();

            return EntryResource::make($entry);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function update(Entry $entry, EntryUpdateRequest $request): EntryResource
    {
        DB::beginTransaction();

        try {
            $entry = $this->entryService->update($entry, $request->validated());
            DB::commit();

            return EntryResource::make($entry);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function delete(Entry $entry): Response
    {
        DB::beginTransaction();

        try {
            $this->entryService->delete($entry);
            DB::commit();

            return response(null, 204);
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
