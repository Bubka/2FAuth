<?php

namespace App\Listeners;

use App\Events\OtpGenerated;
use App\Listeners\Traits\HasRequestIp;
use App\Models\Dto\HotpDto;
use App\Models\Dto\TotpDto;
use App\Models\OtpLog;
use Illuminate\Http\Request;

class LogOtpGeneration
{
    use HasRequestIp;

    /**
     * The current request
     */
    public Request $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(OtpGenerated $event) : void
    {
        // $event->twofaccount
        // $event->owner
        // $event->requester
        // $event->otpDto

        if ($event->twofaccount->id) { // Only log if the 2FA account has been persisted
            $logData = [
                'requester_id'    => $event->requester->id,
                'requester_name'  => $event->requester->name,
                'requester_email' => $event->requester->email,
                'owner_id'        => $event->owner->id,
                'owner_name'      => $event->owner->name,
                'owner_email'     => $event->owner->email,
                'twofaccount_id'  => $event->twofaccount->id,
                'ip_address'      => $this->getRequestIp($this->request),
                'otp_type'        => $event->otpDto->otp_type,
            ];

            $logData['generated_at'] = $event->otpDto instanceof TotpDto ? $event->otpDto->generated_at : now();
            $logData['counter']      = $event->otpDto instanceof HotpDto ? $event->otpDto->counter : null;

            OtpLog::create($logData);
        }
    }
}
