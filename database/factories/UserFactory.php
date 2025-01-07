<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique->firstName(),
            'anonymous' => false,
            'last_activity' => now(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => "password",
            'role_id' => 1
        ];
    }

    /**
     * Sorgt dafür, dass die email nicht verifiziert ist
     *
     * @return Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     *
     * sorgt dafür, dass der user anonym ist
     * @return UserFactory
     */
    public function anonymous()
    {
        return $this->state(function (array $attributes) {
            return [
                'anonymous' => true,
            ];
        });
    }
}
