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

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use function __;

class PasswordResetInitMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $token)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('Password reset'))
            ->view('mail.password_reset_init')
            ->with('token', $this->token);
    }
}
