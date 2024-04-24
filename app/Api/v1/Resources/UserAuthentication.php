<?php

namespace App\Api\v1\Resources;

use Carbon\CarbonInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

/**
 * @property mixed $id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $browser
 * @property string $platform
 * @property string $device
 * @property Carbon|null $login_at
 * @property Carbon|null $logout_at
 * @property bool $login_successful
 * @property string|null $duration
 * @property string|null $login_method
 */
class UserAuthentication extends JsonResource
{
    /**
     * A user agent parser instance.
     *
     * @var mixed
     */
    protected $agent;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->agent = new Agent();
        $this->agent->setUserAgent($resource->user_agent);

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'browser'    => $this->agent->browser(),
            'platform'   => $this->agent->platform(),
            'device'     => $this->agent->deviceType(),
            'login_at'   => $this->login_at
                                      ? Carbon::parse($this->login_at)->toDayDateTimeString()
                                      : null,
            'logout_at' => $this->logout_at
                                      ? Carbon::parse($this->logout_at)->toDayDateTimeString()
                                      : null,
            'login_successful' => $this->login_successful,
            'duration'         => $this->logout_at
                                      ? Carbon::parse($this->logout_at)->diffForHumans(Carbon::parse($this->login_at), ['syntax' => CarbonInterface::DIFF_ABSOLUTE])
                                      : null,
            'login_method'     => $this->login_method,
        ];
    }
}
