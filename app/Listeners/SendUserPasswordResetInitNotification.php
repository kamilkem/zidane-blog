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

namespace App\Listeners;

use App\Events\UserPasswordResetInit;
use App\Mail\PasswordResetInitMail;
use Illuminate\Support\Facades\Mail;

class SendUserPasswordResetInitNotification
{
    public function handle(UserPasswordResetInit $event): void
    {
        Mail::to($event->user)->queue(
            new PasswordResetInitMail($event->token),
        );
    }
}
