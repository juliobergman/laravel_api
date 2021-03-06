<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'avatar' => '/storage/ui/avatar/default/user.jpg',
            'phone' => $this->faker->phoneNumber(),
            'country' => strtolower($this->faker->countryCode()),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'gender' => 'male',
            'site' => $this->faker->domainName(),
        ];
    }
}
