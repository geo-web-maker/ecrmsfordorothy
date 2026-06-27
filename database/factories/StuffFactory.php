<?php

namespace Database\Factories;

use App\Models\Stuff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Stuff>
 */
class StuffFactory extends Factory
{
    protected $model = Stuff::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'email'          => fake()->unique()->safeEmail(),
            'password'       => static::$password ??= 'password',
            'role'           => 'Whistleblower',
            'is_active'      => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function whistleblower(): static
    {
        return $this->state(fn () => ['role' => 'Whistleblower']);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'Admin']);
    }

    public function officer(): static
    {
        return $this->state(fn () => ['role' => 'Officer']);
    }
}
