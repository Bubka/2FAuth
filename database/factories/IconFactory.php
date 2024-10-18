<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Tests\Data\OtpTestData;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Icon>
 */
class IconFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'    => OtpTestData::ICON_PNG,
            'content' => base64_decode(OtpTestData::ICON_PNG_DATA),
        ];
    }

    /**
     * Indicate that the icon is a jpeg image.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Icon>
     */
    public function jpeg()
    {
        return $this->state(function (array $attributes) {
            return [
                'name'    => OtpTestData::ICON_JPEG,
                'content' => base64_decode(OtpTestData::ICON_JPEG_DATA),
            ];
        });
    }

    /**
     * Indicate that the icon is a webp image.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Icon>
     */
    public function webp()
    {
        return $this->state(function (array $attributes) {
            return [
                'name'    => OtpTestData::ICON_WEBP,
                'content' => base64_decode(OtpTestData::ICON_WEBP_DATA),
            ];
        });
    }

    /**
     * Indicate that the icon is a bmp image.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Icon>
     */
    public function bmp()
    {
        return $this->state(function (array $attributes) {
            return [
                'name'    => OtpTestData::ICON_BMP,
                'content' => base64_decode(OtpTestData::ICON_BMP_DATA),
            ];
        });
    }

    /**
     * Indicate that the icon is a svg image.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Icon>
     */
    public function svg()
    {
        return $this->state(function (array $attributes) {
            return [
                'name'    => OtpTestData::ICON_SVG,
                'content' => base64_decode(OtpTestData::ICON_SVG_DATA_ENCODED),
            ];
        });
    }
}