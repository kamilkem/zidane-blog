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

namespace App\Services\Entry;

use App\Models\Entry;

use function auth;
use function now;

class EntryService implements EntryServiceInterface
{
    public function store(array $validated): Entry
    {
        $entry = new Entry();
        $entry->title = $validated['title'];
        $entry->content = $validated['content'];
        $entry->user_id = auth()->user()->id;
        $this->updateEntryPublishedAt($entry, (bool) $validated['published']);
        $entry->save();

        return $entry;
    }

    public function update(Entry $entry, array $validated): Entry
    {
        $entry->title = $validated['title'] ?? $entry->title;
        $entry->content = $validated['content'] ?? $entry->content;
        $this->updateEntryPublishedAt($entry, (bool) $validated['published']);
        $entry->save();

        return $entry;
    }

    public function delete(Entry $entry): ?bool
    {
        return $entry->delete();
    }

    protected function updateEntryPublishedAt(Entry $entry, bool $published): void
    {
        if (!$published) {
            $entry->published_at = null;
        }

        if ($published && !$entry->published_at) {
            $entry->published_at = now();
        }
    }
}
