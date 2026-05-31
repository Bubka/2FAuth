<?php

namespace App\Notifications\Traits;

trait RendersTwoFAccount
{
    /**
     * Render a printable label for a TwoFAccount in notifications
     */
    private function twoFAccountLabel() : string
    {
        $service = trim((string) ($this->twofaccount->service ?? ''));
        $account = trim((string) $this->twofaccount->account);

        if ($service === '') {
            return $account;
        }

        return sprintf('%s (%s)', $service, $account);
    }
}
