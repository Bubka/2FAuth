<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
 */
class OtpLogFactory extends Factory
{
    public const IP = '127.0.0.1';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'requester_id'     => null,
            'requester_name'   => 'N/A',
            'requester_email'  => $this->faker->safeEmail(),
            'owner_id'    => null,
            'owner_name'  => 'N/A',
            'owner_email' => $this->faker->safeEmail(),
            'twofaccount_id' => null,
            'ip_address'   => self::IP,
            'otp_type' => 'totp',
            'generated_at' => now(),
        ];
    }

    /**
     * Indicate that the model has been generated for a HOTP account.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function hotp()
    {
        return $this->state(function (array $attributes) {
            return [
                'otp_type'     => 'hotp',
                'counter'      => 5,
            ];
        });
    }

    /**
     * Indicate that the model has been generated at the specified date.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function at(Carbon $at)
    {
        return $this->state(function (array $attributes) use ($at) {
            return [
                'generated_at' => $at,
            ];
        });
    }

    /**
     * Indicate that the model has been generated before last year.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function daysAgo(int $days)
    {
        return $this->state(function (array $attributes) use ($days) {
            $generationDate = now()->subDays($days);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }

    /**
     * Indicate that the model has been generated during last month.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function duringLastMonth()
    {
        return $this->state(function (array $attributes) {
            $generationDate  = now()->subDays(15);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }

    /**
     * Indicate that the model has been generated during the last 3 months.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function duringLastThreeMonth()
    {
        return $this->state(function (array $attributes) {
            $generationDate  = now()->subMonths(2);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }

    /**
     * Indicate that the model has been generated during the last 6 months.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function duringLastSixMonth()
    {
        return $this->state(function (array $attributes) {
            $generationDate  = now()->subMonths(4);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }

    /**
     * Indicate that the model has been generated during the last year.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function duringLastYear()
    {
        return $this->state(function (array $attributes) {
            $generationDate  = now()->subMonths(10);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }

    /**
     * Indicate that the model has been generated before last year.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpLog>
     */
    public function beforeLastYear()
    {
        return $this->state(function (array $attributes) {
            $generationDate  = now()->subYears(2);

            return [
                'generated_at' => $generationDate,
            ];
        });
    }
}