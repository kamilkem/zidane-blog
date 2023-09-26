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

interface EntryServiceInterface
{
    public function store(array $validated): Entry;

    public function update(Entry $entry, array $validated): Entry;

    public function delete(Entry $entry): ?bool;
}
