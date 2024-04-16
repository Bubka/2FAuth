<?php

namespace Tests\Data;

class AuthenticationLogData
{
    /**
     * Indicate that the model should have login date.
     *
     * @return array
     */
    public static function failedLogin()
    {
        $loginDate = now()->subDays(15);
    
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => $loginDate,
            'login_successful' => false,
            'logout_at' => null,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have no login date
     *
     * @return array
     */
    public static function noLogin()
    {
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => null,
            'login_successful' => false,
            'logout_at' => now(),
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have no logout date
     *
     * @return array
     */
    public static function noLogout()
    {
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => now(),
            'login_successful' => true,
            'logout_at' => null,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have login during last month
     *
     * @return array
     */
    public static function duringLastMonth()
    {
        $loginDate = now()->subDays(15);
        $logoutDate = $loginDate->addHours(1);
    
        return [
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0',
            'login_at' => $loginDate,
            'login_successful' => true,
            'logout_at' => $logoutDate,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have login during last 3 month
     *
     * @return array
     */
    public static function duringLastThreeMonth()
    {
        $loginDate = now()->subMonths(2);
        $logoutDate = $loginDate->addHours(1);
    
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => $loginDate,
            'login_successful' => true,
            'logout_at' => $logoutDate,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have login during last 6 month
     *
     * @return array
     */
    public static function duringLastSixMonth()
    {
        $loginDate = now()->subMonths(4);
        $logoutDate = $loginDate->addHours(1);
    
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => $loginDate,
            'login_successful' => true,
            'logout_at' => $logoutDate,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have login during last month
     *
     * @return array
     */
    public static function duringLastYear()
    {
        $loginDate = now()->subMonths(10);
        $logoutDate = $loginDate->addHours(1);
    
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => $loginDate,
            'login_successful' => true,
            'logout_at' => $logoutDate,
            'location' => null,
        ];
    }

    /**
     * Indicate that the model should have login during last month
     *
     * @return array
     */
    public static function beforeLastYear()
    {
        $loginDate = now()->subYears(2);
        $logoutDate = $loginDate->addHours(1);
    
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'login_at' => $loginDate,
            'login_successful' => true,
            'logout_at' => $logoutDate,
            'location' => null,
        ];
    }
}
